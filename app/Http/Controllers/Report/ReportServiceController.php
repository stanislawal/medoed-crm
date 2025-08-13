<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Payment\Payment;
use App\Models\Project\Project;
use App\Models\Service\Service;
use App\Repositories\Report\ServiceRepositories;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportServiceController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $reports = ServiceRepositories::getReport($startDate, $endDate);

        $indicators = $this->calculate($reports);

        $reports->with([
            'projectClients',
            'projectUser',
            'leadingSpecialist',
            'monthlyAccruals' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('monthly_accruals.date', [$startDate, $endDate]);
            },
            'services.serviceType'
        ]);

        $reports = $reports->paginate(20);

        return view('report.service.service_list', [
            'reports'    => $reports,
            'indicators' => $indicators
        ]);
    }

    public function show(Request $request, $id)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $services = Service::on()
            ->with(['specialists', 'serviceType'])
            ->where('project_id', $id)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfMonth()->toDateTimeString(),
                Carbon::parse($endDate)->endOfMonth()->toDateTimeString()
            ])
            ->paginate(20);

        // общая сумма оплаты за проект
        $payment = Payment::on()->selectRaw("
            project_id,
            sum(
                coalesce(sber_a, 0) +
                coalesce(tinkoff_a, 0) +
                coalesce(tinkoff_k, 0) +
                coalesce(sber_d, 0) +
                coalesce(sber_k, 0) +
                coalesce(privat, 0) +
                coalesce(um, 0) +
                coalesce(wmz, 0) +
                coalesce(birja, 0)
            ) as amount,
            count(id) as count_operation
        ")
            ->where('project_id', $id)
            ->where('mark', true)
            ->whereBetween('date', [
                Carbon::parse($startDate)->startOfMonth()->toDateTimeString(),
                Carbon::parse($endDate)->endOfMonth()->toDateTimeString(),
            ])
            ->groupBy(['project_id'])
            ->get()
            ->toArray();

        // список проектов для модалки создания оплаты
        $projects = Project::on()->select('id', 'project_name')->get()->toArray();

        // информация о проекте, клиенты проекта
        $project = Project::on()->with('projectClients')->select(['id', 'project_name'])->find($id);

        // история оплат в выбранном месяце
        $paymentHistory = Payment::on()->where('project_id', $id)
            ->whereBetween('date', [
                Carbon::parse($startDate)->startOfMonth()->toDateTimeString(),
                Carbon::parse($endDate)->endOfMonth()->toDateTimeString(),
            ])
            ->get()->toArray();

        $remainderDuty = ServiceRepositories::duty(
            $id,
            Carbon::parse($startDate)->subDays(1)->endOfMonth()->toDateTimeString(),
        );

        $duty = ServiceRepositories::duty(
            $id,
            Carbon::parse($endDate)->endOfMonth()->toDateTimeString(),
        );

        return view('report.service.service_item', [
            'services'       => $services,
            'project'        => $project,
            'projects'       => $projects,
            'payment'        => $payment,
            'paymentHistory' => $paymentHistory,
            'remainderDuty'  => $remainderDuty,
            'duty'           => $duty,
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
            sum(projects.sum_amount) as sum_amount,
            sum(projects.sum_accrual_this_month) as sum_accrual_this_month,
            sum(projects.duty) as sum_duty
        ")
            ->fromSub($reports, 'projects')
            ->first()
            ->toArray();
    }
}
