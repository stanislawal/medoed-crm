<?php

namespace App\Repositories\Report;

use App\Models\Payment\Payment;
use App\Models\Project\MonthlyAccrual;
use App\Models\Project\Project;
use App\Models\Service\Service;
use Carbon\Carbon;

class ServiceRepositories
{

    /**
     * Возвращает отчет по услугам
     *
     * @param $startDate
     * @param $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReport($startDate, $endDate)
    {
        // сумма общего договора в указанном месяце
        $monthlyAccruals = MonthlyAccrual::on()->selectRaw("
            'project_id',
            SUM(amount) as sum_amount
        ")->whereBetween('date', [$startDate, $endDate])->groupBy('project_id');

        // сумма начислений в этом месяце
        $servicesProjectThisMonth = Service::on()->selectRaw("
            project_id,
            SUM(accrual_this_month) as sum_accrual_this_month
        ")->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfMonth()->toDateTimeString(),
            Carbon::parse($endDate)->endOfMonth()->toDateTimeString()
        ])->groupBy('project_id');

        $servicesProjectAllPeriod = Service::on()->selectRaw("
            project_id,
            SUM(accrual_this_month) as sum_accrual_this_month_duty
        ")->where('created_at', '<=', Carbon::parse($endDate)->endOfMonth()->toDateTimeString())
            ->groupBy('project_id');

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
            projects.requisite_id,
            projects.status_id,
            projects.duty_on_services,
            projects.data_start_work,
            projects.promoting_website,
            projects.checkbox_in_service,
            (SELECT MIN(services_project.created_at)
                FROM services_project
                WHERE services_project.project_id = projects.id
            ) as first_service_date,
            SUM(monthly_accruals.sum_amount) as sum_amount,
            SUM(services_project.sum_accrual_this_month) as sum_accrual_this_month,
            SUM(services_project_duty.sum_accrual_this_month_duty) as sum_accrual_this_month_duty
        ")
            ->from('projects')

            // сумма договора в указанном месяце
            ->leftJoinSub($monthlyAccruals, 'monthly_accruals', function ($join) {
                $join->on('projects.id', '=', 'monthly_accruals.project_id');
            })

            ->leftJoinSub($servicesProjectThisMonth, 'services_project', function ($join) {
                $join->on('projects.id', '=', 'services_project.project_id');
            })
            ->leftJoinSub($servicesProjectAllPeriod, 'services_project_duty', function ($join) {
                $join->on('projects.id', '=', 'services_project_duty.project_id');
            })

            ->groupBy('projects.id')
            ->where(function ($where) {
                $where->whereHas('services')->orWhere('duty_on_services', '>', 0);
            });

        $reports = Project::on()->selectRaw("
            projects.*,
            (
                projects.sum_accrual_this_month_duty
                -
                SUM(
                    COALESCE(payment.sber_a, 0) +
                    COALESCE(payment.tinkoff_a, 0) +
                    COALESCE(payment.tinkoff_k, 0) +
                    COALESCE(payment.sber_d, 0) +
                    COALESCE(payment.sber_k, 0) +
                    COALESCE(payment.privat, 0) +
                    COALESCE(payment.um, 0) +
                    COALESCE(payment.wmz, 0) +
                    COALESCE(payment.birja, 0)
                )
                +
                projects.duty_on_services
            ) as duty
        ")
            ->fromSub($reports, 'projects')
            ->leftJoin('payment', function ($leftJoin) use ($endDate) {
                $leftJoin->on('payment.project_id', '=', 'projects.id')->where('payment.mark', 1)
                    ->where('payment.date', '<=', Carbon::parse($endDate)->endOfMonth()->toDateTimeString());
            })
            ->groupBy('projects.id');

        return $reports;
    }

    public static function duty($projectId, $date)
    {
        $payment = Payment::on()->selectRaw("
            COALESCE(
                SUM(
                    COALESCE(sber_a,0) +
                    COALESCE(tinkoff_a,0) +
                    COALESCE(tinkoff_k,0) +
                    COALESCE(sber_d,0) +
                    COALESCE(sber_k,0) +
                    COALESCE(privat,0) +
                    COALESCE(um,0) +
                    COALESCE(wmz,0) +
                    COALESCE(birja,0)
                )
            , 0) as payment
        ")
            ->where('project_id', $projectId)
            ->where('mark', true)
            ->where('date', '<=', $date)
            ->first()->payment ?? 0;

        $service = Service::on()->selectRaw("
            COALESCE(SUM(accrual_this_month), 0) as sum_accrual_this_month
        ")
            ->where('project_id', $projectId)
            ->where('created_at', '<=', $date)
            ->first()->sum_accrual_this_month ?? 0;

        $dutyOnServices = Project::on()->selectRaw("duty_on_services")->find($projectId)->duty_on_services;

        return $service - $payment + $dutyOnServices;
    }
}
