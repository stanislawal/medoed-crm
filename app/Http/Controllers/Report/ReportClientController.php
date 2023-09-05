<?php

namespace App\Http\Controllers\Report;

use App\Export\Export;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Payment\Payment;
use App\Models\Project\Project;
use App\Models\Project\Style;
use App\Models\Project\Theme;
use App\Models\Rate\Rate;
use App\Models\StatusPaymentProject;
use App\Models\User;
use App\Repositories\Report\ClientRepositories;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportClientController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {

        [$startDate, $endDate] = $this->monthElseRange($request);

        // получить запрос отчета
        $reportQuery = ClientRepositories::getReport($request, $startDate, $endDate);
        $statistict = ClientRepositories::getReport($request, $startDate, $endDate);

        $reportQuery->when(UserHelper::isManager(), function ($where) {
            $where->where('manager_id', UserHelper::getUserId());
        });

        $statistict->when(UserHelper::isManager(), function ($where) {
            $where->where('manager_id', UserHelper::getUserId());
        });

        // фильтр
        $this->filter($reportQuery, $request);
        $this->filter($statistict, $request);

        // результат запроса
        $reports = $reportQuery->paginate(20);

        // расчеты
        $statistics = Project::on()->selectRaw("
             (sum(result.finish_duty) + sum(result.duty) + sum(result.remainder_duty)) as finish_duty,
             sum(result.sum_without_space) as sum_without_space,
             sum(result.sum_gross_income) as sum_gross_income,
             sum(result.profit) as profit,
             (sum(result.sum_price_client) / count(result.id)) as middle_check,
             sum(result.symbol_in_day) as sum_symbols_in_day
        ")->fromSub($statistict, 'result')
            ->get()
            ->first()
            ->toArray();

        $rates = Rate::on()->get();

        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get();

        $project = Project::on()->select(['id', 'project_name'])
            ->with(['projectAuthor', 'projectClients'])
            ->get()->toArray();

        $clients = Client::on()->get()->toArray();

        $themes = Theme::on()->get()->toArray();

        $priorities = Style::on()->get()->toArray();

        return view('report.client.list', [
            'reports' => $reports,
            'statistics' => $statistics,
            'rates' => $rates,
            'statusPayments' => StatusPaymentProject::on()->get()->toArray(),
            'managers' => $managers,
            'project' => $project,
            'clients' => $clients,
            'themes' => $themes,
            'priorities' => $priorities,
        ]);
    }

    /**
     * Фильтр к отчету
     *
     * @param Builder $reports
     * @param $request
     * @return void
     */
    private function filter(Builder &$reports, $request)
    {

        // менеджер
        if (!empty($request->manager_id)) {
            $reports->where('manager_id', $request->manager_id);
        }

        // долг
        if (!is_null($request->duty_from ?? null) || !is_null($request->duty_to ?? null)) {
            $reports->whereBetween('finish_duty', [(float)$request->duty_from ?? 0, (float)$request->duty_to ?? 999999999]);
        }

        // объем ЗБП
        if (!empty($request->sum_without_space_from) || !empty($request->sum_without_space_to)) {
            $reports->whereBetween('sum_without_space', [$request->sum_without_space_from ?? 0, $request->sum_without_space_to ?? 999999999]);
        }

        // маржа
        if (!empty($request->profit_from) || !empty($request->profit_to)) {
            $reports->whereBetween('profit', [$request->profit_from ?? 0, $request->profit_to ?? 999999999]);
        }

        // срок в работе
        if (!empty($request->date_diff_from) || !empty($request->date_diff_to)) {
            $reports->whereBetween('date_diff', [$request->date_diff_from ?? 0, $request->date_diff_to ?? 999999999]);
        }

        // менеджер
        if (!empty($request->project_id)) {
            $reports->where('projects.id', $request->project_id);
        }

        // менеджер
        if (!empty($request->client_id)) {
            $reports->whereHas('projectClients', function ($where) use ($request) {
                $where->where('clients.id', $request->client_id);
            });
        }

        // тема
        if (!empty($request->theme_id)) {
            $reports->where('projects.theme_id', $request->theme_id);
        }

        // приоритет
        if (!empty($request->style_id)) {
            $reports->where('projects.style_id', $request->style_id);
        }
    }

    /**
     * Подробная информация о проекте
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, $id)
    {
        // общая сумма оплаты за проект
        $payment = Payment::on()->selectRaw("
            project_id,
            sum(sber_a + tinkoff_a + tinkoff_k + sber_d + sber_k + privat + um + wmz + birja) as amount,
            count(id) as count_operation
        ")
            ->where('project_id', $id)
            ->where('mark', true)
            ->whereBetween('date', [
                Carbon::parse($request->month)->startOfMonth()->toDateTimeString(),
                Carbon::parse($request->month)->endOfMonth()->toDateTimeString(),
            ])
            ->groupBy(['project_id'])
            ->get()
            ->toArray();

        $paymentHistory = Payment::on()->where('project_id', $id)
            ->whereBetween('date', [
                Carbon::parse($request->month)->startOfMonth()->toDateTimeString(),
                Carbon::parse($request->month)->endOfMonth()->toDateTimeString(),
            ])
            ->get()->toArray();

        $clients = Client::on()->whereHas('projectClients', function ($where) use ($id) {
            $where->where('projects.id', $id);
        })->get()->toArray();

        $report = ClientRepositories::getByProject($id, $request)->get()->toArray();

        $project = Project::on()->select(['duty', 'id', 'project_name'])->find($id);

        $remainderDuty = ClientRepositories::getDuty(
            Carbon::parse($request->month)->startOfMonth()->subDay()->toDateString(),
            $id
        )->first()->remainder_duty;

        return view('report.client.item', [
            'report' => collect($report),
            'clients' => $clients,
            'payment' => $payment,
            'paymentHistory' => $paymentHistory,
            'projectId' => $id,
            'project' => $project,
            'remainderDuty' => $remainderDuty,
        ]);
    }

    public function monthElseRange($request)
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

    public function exportAll(Request $request){

        [$startDate, $endDate] = $this->monthElseRange($request);

        // получить запрос отчета
        $reportQuery = ClientRepositories::getReport($request, $startDate, $endDate);
        $statistict = ClientRepositories::getReport($request, $startDate, $endDate);

        $reportQuery->when(UserHelper::isManager(), function ($where) {
            $where->where('manager_id', UserHelper::getUserId());
        });

        $statistict->when(UserHelper::isManager(), function ($where) {
            $where->where('manager_id', UserHelper::getUserId());
        });

        // фильтр
        $this->filter($reportQuery, $request);
        $this->filter($statistict, $request);

        // результат запроса
//        $reports = $reportQuery->get()
//            ->map(function ($item){
//               return [$item->id, $item->remainder_duty, $item->project_name, $item->заказчик,
//                   $item->sum_without_space, $item->sum_gross_income, $item->маржа, $item->менеджер, $item->payment_terms, $item->sum_price_client, $item->sum_price_author, $item->symbol_in_day,
//                $item->дата ]
//            });

//        dd($reports);

        // расчеты
        $statistics = Project::on()->selectRaw("
             (sum(result.finish_duty) + sum(result.duty) + sum(result.remainder_duty)) as finish_duty,
             sum(result.sum_without_space) as sum_without_space,
             sum(result.sum_gross_income) as sum_gross_income,
             sum(result.profit) as profit,
             (sum(result.sum_price_client) / count(result.id)) as middle_check,
             sum(result.symbol_in_day) as sum_symbols_in_day
        ")->fromSub($statistict, 'result')
            ->get()
            ->first();

        $data = [
            ['Дата', '29.10.3492'],
            ['Долг', '123123'],
            ['ЗБП', '12321354536'],
            ['ВД', '123145654654623'],
            [''],
            ['ID', 'Состояние', 'Долг', 'Проект', 'Заказчик'],
            ['value', 'value', 'value', 'value', 'value'],
            ['value1', 'value', 'value', 'value', 'value'],
            ['value2', 'value', 'value', 'value', 'value'],
            ['value3', 'value', 'value', 'value', 'value'],
        ];

        $export = new Export($data);

        return Excel::download($export, 'excel_client_report.xlsx');

    }

    public function exportById(){

    }
}
