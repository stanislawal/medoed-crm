<?php

namespace App\Http\Controllers\Report;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Models\Project\Project;
use App\Models\Requisite;
use App\Models\Service\Service;
use App\Models\Service\ServiceType;
use App\Models\Service\SpecialistService;
use App\Models\Status;
use App\Models\User;
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

        $reports->when(UserHelper::isManager(), function (Builder $builder) {
            $builder->where('projects.manager_id', UserHelper::getUserId());
        });

        $this->filter($reports, $request);

        $indicators = $this->calculate($reports);

        $reports->with([
            'projectClients',
            'projectUser',
            'leadingSpecialist',
            'monthlyAccruals' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('monthly_accruals.date', [$startDate, $endDate]);
            },
            'services.serviceType',
            'requisite'
        ]);

        $reports = $reports->paginate(20);

        $serviceType = ServiceType::on()->get();
        $projects = Project::on()->select(['id', 'project_name'])
            ->where(function ($where) {
                $where->whereHas('services')->orWhere('duty_on_services', '>', 0);
            })->get();

        $specialistService = SpecialistService::on()->get();
        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get();
        $requisite = Requisite::on()->get();

        return view('report.service.service_list', [
            'reports'           => $reports,
            'indicators'        => $indicators,
            'statuses'          => Status::on()->get(),
            'serviceType'       => $serviceType,
            'projects'          => $projects,
            'specialistService' => $specialistService,
            'managers'          => $managers,
            'requisite'         => $requisite,
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
        $projects = Project::on()->select(['id', 'project_name'])->get();

        // информация о проекте, клиенты проекта
        $project = Project::on()->with('projectClients')->select(['id', 'project_name', 'duty_on_services'])->find($id);

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

    private function filter(Builder &$reports, Request $request)
    {

        $reports->when(!empty($request->service_type_id), function ($query) use ($request) {
            $query->whereHas('services', function ($query) use ($request) {
                $query->where('service_type_id', $request->service_type_id);
            });
        });

        $reports->when(!empty($request->project_id), function ($query) use ($request) {
            $query->where('projects.id', $request->project_id);
        });

        $reports->when(!empty($request->legal_name_company), function ($query) use ($request) {
            $query->where('projects.legal_name_company', 'like', "%{$request->legal_name_company}%");
        });

        $reports->when(!empty($request->status_id), function ($query) use ($request) {
            $query->where('projects.status_id', $request->status_id);
        });

        $reports->when(!empty($request->leading_specialist_id), function ($query) use ($request) {
            $query->where('projects.leading_specialist_id', $request->leading_specialist_id);
        });

        $reports->when(!empty($request->manager_id), function ($query) use ($request) {
            $query->where('projects.manager_id', $request->manager_id);
        });

        $reports->when(!empty($request->requisite_id), function ($query) use ($request) {
            $query->where('projects.requisite_id', $request->requisite_id);
        });
    }
}
