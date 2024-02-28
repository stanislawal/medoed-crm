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
use Illuminate\Support\Collection;
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

        // Текущий рабочий день
        $diffInCurrentDay = \Illuminate\Support\Carbon::parse($startDate)->diffInWeekdays(Carbon::parse(now())) + 1;

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
             (sum(result.sum_gross_income) / (sum(result.sum_without_space) / 1000)) as middle_check,
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

        $paymentMonth = Payment::on()->selectRaw("
            count(id) as count_payment,
            coalesce(sum(sber_a+sber_d+sber_k+tinkoff_a+tinkoff_k+privat+um+wmz+birja), 0) as all_sum
        ")
            ->where('mark', true)
            ->whereBetween('date', [
                Carbon::parse(now())->startOfMonth()->format('Y-m-d'),
                Carbon::parse(now())->endOfMonth()->format('Y-m-d'),
            ])
            ->first()
            ->toArray();

        return view('report.client.client_list', [
            'reports'          => $reports,
            'statistics'       => $statistics,
            'diffInCurrentDay' => $diffInCurrentDay,
            'rates'            => $rates,
            'statusPayments'   => StatusPaymentProject::on()->get()->toArray(),
            'managers'         => $managers,
            'project'          => $project,
            'clients'          => $clients,
            'themes'           => $themes,
            'priorities'       => $priorities,
            'paymentMonth'     => $paymentMonth,
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
            $reports->whereIn('manager_id', $request->manager_id ?? []);
        }

        // долг
        if (!is_null($request->duty_from ?? null) || !is_null($request->duty_to ?? null)) {
            $reports->whereBetween('all_sum_duty', [(float)$request->duty_from ?? -9999999, (float)
                                                    $request->duty_to
                                                        ?? 9999999]);
        }

        // объем ЗБП
        if (!empty($request->sum_without_space_from) || !empty($request->sum_without_space_to)) {
            $reports->whereBetween('sum_without_space', [$request->sum_without_space_from ?? 0,
                                                         $request->sum_without_space_to ?? 999999999]);
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

        // состояние оплаты
        $reports->when(!empty($request->status_payment_id), function (Builder $orderBy) use ($request) {
            $orderBy->whereIn('status_payment_id', $request->status_payment_id ?? []);
        });

        // состояние оплаты (игнорировать)
        $reports->when(!empty($request->ignore_status_payment_id), function (Builder $orderBy) use ($request) {
            $orderBy->whereNotIn('status_payment_id', $request->ignore_status_payment_id ?? []);
        });

        // сортировка
        $reports->when(!empty($request->sort), function (Builder $orderBy) use ($request) {
            $orderBy->orderBy($request->sort, $request->direction);
        });
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

        return view('report.client.client_item', [
            'report'         => collect($report),
            'clients'        => $clients,
            'payment'        => $payment,
            'paymentHistory' => $paymentHistory,
            'projectId'      => $id,
            'project'        => $project,
            'remainderDuty'  => $remainderDuty
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

    #---------Экспорт одного проекта--------------

    public function exportItem(Request $request, $id)
    {
        $report = ClientRepositories::getByProject($id, $request)->get();
        $headers = $this->headersItem();
        $results = $this->resultsItem($id, $request, $report);
        $table = $this->getTableForExportItem($report);

        $export = array_merge($results, $headers, $table);

        $export = new Export($export);

        return Excel::download($export, "export_client_report_{$id}.xlsx");

    }

    private function resultsItem($id, $request, $report)
    {
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
            ->get();

        $remainderDuty = ClientRepositories::getDuty(
            Carbon::parse($request->month)->startOfMonth()->subDay()->toDateString(),
            $id
        )->first()->remainder_duty;
        $project = Project::on()->select(['duty', 'id', 'project_name'])->find($id);

        $clientList = Client::on()->whereHas('projectClients', function ($where) use ($id) {
            $where->where('projects.id', $id);
        })->get();

        $clients = '';
        foreach ($clientList as $client) {
            $clients = $clients . ' ' . $client->name;
        }
        return [
            ['Проект', $project['project_name']],
            ['Заказчик', $clients],
            ['Маржа', $report->sum('margin')],
            ['Сдано ЗБП', $report->sum('without_space')],
            ['Долг', $report->sum('price_article') - $payment->sum('amount') + $remainderDuty],
            [' ']
        ];

    }

    private function headersItem()
    {
        return [
            [' '],
            ['ID', 'Автор', 'Дата сдачи статьи', 'Название статьи', 'ЗБП', 'Цена заказчика', 'Cумма', 'Цена автора', 'Маржа']
        ];
    }

    private function getTableForExportItem(Collection $report)
    {
        return $report
            ->map(function ($item) {

                return [
                    $item->id,
                    $item->articleAuthor->first()['full_name'],
                    $item->created_at,
                    $item->article_name,
                    $item->without_space,
                    $item->price_client,
                    $item->price_article,
                    $item->price_author,
                    $item->margin,
                ];
            })->toArray();
    }

    #---------Экспорт одного проекта--------------


    #---------Экспорт всего свода заказчиков--------------

    public function exportAll(Request $request)
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

        $results = $this->getResults($statistict);
        $headers = $this->getHeaders();
        $table = $this->getTableForExport($reportQuery);

        $export = array_merge($results, $headers, $table);


        $export = new Export($export);

        return Excel::download($export, 'excel_client_report.xlsx');
    }

    private function getTableForExport(Builder $reportQuery)
    {
        return $reportQuery->get()
            ->map(function ($item) {
                $clients = '';
                foreach ($item->projectClients as $client)
                    $clients = $clients . ' ' . $client->name;
                return [
                    $item->id,
                    $item->remainder_duty,
                    $item->project_name,
                    $item->projectTheme['name'] ?? '',
                    $item->projectStyle['name'] ?? '',
                    $clients,
                    $item->sum_without_space,
                    $item->sum_gross_income,
                    $item->profit,
                    $item->projectUser['full_name'] ?? '',
                    $item->payment_terms,
                    $item->sum_price_client,
                    $item->sum_price_author,
                    $item->symbol_in_day,

                ];
            })->toArray();
    }

    private function getResults(Builder $statistict)
    {
        $statistics = Project::on()->selectRaw("
             (sum(result.finish_duty) + sum(result.duty) + sum(result.remainder_duty)) as finish_duty,
             sum(result.sum_without_space) as sum_without_space,
             sum(result.sum_gross_income) as sum_gross_income,
             sum(result.profit) as profit,
             (sum(result.sum_gross_income) / (sum(result.sum_without_space) / 1000)) as middle_check,
             sum(result.symbol_in_day) as sum_symbols_in_day
        ")->fromSub($statistict, 'result')
            ->get()
            ->first();
        return [
            ['Общий долг', $statistics['finish_duty']],
            ['Общий объем ЗБП', $statistics['sum_without_space']],
            ['ВД', $statistics['sum_gross_income']],
            ['Маржа', $statistics['profit']],
            ['Средний чек', $statistics['middle_check']],
        ];

    }

    private function getHeaders()
    {
        return [
            [' '],
            ['ID', 'Долг', 'Проект', 'Тема', 'Приоритетность', 'Заказчик', 'ЗБП', 'ВД', 'Маржа', 'Менеджер', 'Условия оплаты', 'Цена заказчика', 'Цена автора', 'Символов в день']
        ];
    }
}
#---------Экспорт всего свода заказчиков--------------
