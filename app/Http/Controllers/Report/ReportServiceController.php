<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Service\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportServiceController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $reports = Project::on()->selectRaw("
            projects.id,
            projects.manager_id,
            projects.project_name,
            projects.project_theme_service,
            projects.reporting_data,
            projects.terms_payment,
            projects.region,
            projects.passport_to_work_plan,
            projects.total_amount_agreement,
            projects.hours,
            projects.legal_name_company,
            projects.leading_specialist_id,
            (SELECT MIN(services_project.created_at)
                FROM services_project
                WHERE services_project.project_id = projects.id
            ) as first_service_date,
            SUM(COALESCE(monthly_accruals.amount, 0)) as sum_amount
        ")
            ->with([
                'projectClients',
                'projectUser',
                'leadingSpecialist',
                'monthlyAccruals' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('monthly_accruals.date', [$startDate, $endDate]);
                },
                'services.serviceType'
            ])
            ->from('projects')
            ->leftJoin('monthly_accruals', function ($query) use ($startDate, $endDate) {
                $query->on('monthly_accruals.project_id', '=', 'projects.id')
                    ->whereBetween('monthly_accruals.date', [$startDate, $endDate]);
            })
            ->groupBy('projects.id')
            ->whereHas('services');

        $indicators = $this->calculate($reports);

        //        dd([$startDate, $endDate], $reports->get()->toArray());

        $reports = $reports->paginate(20);

        return view('report.service.service_list', [
            'reports'    => $reports,
            'indicators' => $indicators
        ]);
    }

    public function show(Request $request, $id)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $reports = Service::on()
            ->with(['specialists', 'serviceType'])
            ->where('project_id', $id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->paginate(20);

        return view('report.service.service_item', [
            'reports' => $reports,
            'project' => Project::on()->select(['id', 'project_name'])->find($id),
        ]);
    }

    private function monthElseRange($request)
    {
        if (!empty($request->month)) {
            $startDate = Carbon::parse($request->month)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($request->month)->endOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($request->start_date ?? now()->startOfMonth())->format('Y-m-d');
            $endDate = Carbon::parse($request->end_date ?? now()->endOfMonth())->format('Y-m-d');
        }

        return [$startDate, $endDate];
    }

    private function calculate(Builder $reports)
    {
        return Project::on()->selectRaw("
            sum(projects.total_amount_agreement) as sum_total_amount_agreement,
            sum(projects.sum_amount) as sum_amount
        ")
            ->fromSub($reports, 'projects')
            ->first()
            ->toArray();
    }
}
