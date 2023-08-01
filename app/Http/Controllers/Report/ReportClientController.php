<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Client\Client;
use App\Models\Payment\Payment;
use App\Models\Project\Project;
use App\Models\Rate\Rate;
use App\Models\StatusPaymentProject;
use App\Models\User;
use App\Repositories\Report\ClientRepositories;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportClientController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // получить запрос отчета
        $reportQuery = ClientRepositories::getReport();
        $statistict = ClientRepositories::getReport();

        // фильтр
        $this->filter($reportQuery, $request);
        $this->filter($statistict, $request);

        // результат запроса
        $reports = $reportQuery->paginate(20);

        // расчеты
        $statistics = Project::on()->selectRaw("
             (sum(result.finish_duty) + sum(result.duty)) as finish_duty,
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

        return view('report.client.list', [
            'reports' => $reports,
            'statistics' => $statistics,
            'rates' => $rates,
            'statusPayments' => StatusPaymentProject::on()->get()->toArray(),
            'managers' => $managers,
            'project' => $project,
            'clients' => $clients
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

        // дата с какой
        if (!empty($request->start_date)) {
            $reports->where('created_at', '>=', Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s'));
        }

        // дата до какой
        if (!empty($request->end_date)) {
            $reports->where('created_at', '<=', Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s'));
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
            $reports->where('id', $request->project_id);
        }

        // менеджер
        if (!empty($request->client_id)) {
            $reports->whereHas('projectClients', function ($where) use ($request) {
                $where->where('clients.id', $request->client_id);
            });
        }
    }

    /**
     * Подробная информация о проекте
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $payment = Payment::on()->selectRaw("
            project_id,
            sum(sber_a + tinkoff_a + sber_d + sber_k + privat + um + wmz + birja) as amount,
            count(id) as count_operation
        ")
            ->groupBy(['project_id'])
            ->where('project_id', $id)
            ->where('mark', true)
            ->get()
            ->toArray();

        $clients = Client::on()->whereHas('projectClients', function ($where) use ($id) {
            $where->where('projects.id', $id);
        });

        // позвращает месяца и количества статей в них
        $links = Article::on()->selectRaw("
                DATE_FORMAT(created_at, '01-%m-%Y') as date,
                count(id) as count
        ")->where('project_id', $id)
            ->groupBy('date')
            ->get()
            ->map(function ($item) use ($id) {
                return [
                    'link' => route('report_client.get_by_month', ['project_id' => $id, 'date' => $item['date']]),
                    'date' => $item['date'],
                    'count' => $item['count']
                ];
            })->toArray();

        $duty = Project::on()->find($id)->duty;

        return view('report.client.item', [
            'report' => collect(ClientRepositories::getByProject($id)->get()->toArray()),
            'clients' => $clients,
            'payment' => $payment,
            'paymentHistory' => Payment::on()->where('project_id', $id)->get()->toArray(),
            'projectId' => $id,
            'duty' => $duty,
            'links' => $links,
        ]);
    }

    /**
     * Возвращает все статьи относящиеся к проекту за указанный месяц
     */
    public function getByMonth(Request $request)
    {
        $dateBetween = [
            Carbon::parse($request->date)->startOfMonth()->toDateTimeString(),
            Carbon::parse($request->date)->endOfMonth()->toDateTimeString(),
        ];

        $articles = ClientRepositories::getByProject($request->project_id)
        ->whereBetween('articles.created_at', $dateBetween);

    }
}
