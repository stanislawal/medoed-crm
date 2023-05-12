<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Project\Project;
use App\Models\Rate\Rate;
use App\Models\StatusPaymentProject;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportClientController extends Controller
{

    public function index(Request $request)
    {
        $reports = Project::on()
            ->selectRaw("
                projects.*,
                SUM(articles.without_space) as sum_without_space,
                SUM(
                    (COALESCE(articles.without_space, 0) * (COALESCE(articles.price_client, 0) / 1000))
                ) as sum_gross_income,
                ABS(datediff(
                    projects.start_date_project,
                    COALESCE(projects.end_date_project, CURRENT_DATE())
                )) as date_diff,
                SUM(articles.price_client) as sum_price_client,
                SUM(articles.price_author) as sum_price_author

        ")->from('projects')
            ->leftJoin('articles', 'articles.project_id', '=', 'projects.id')
            ->groupBy(['projects.id']);

        $reports = Project::on()
            ->selectRaw("
            project.*,
            (sum_without_space / date_diff) as symbol_in_day,
            (sum_price_client - sum_price_author) as profit,

            (
                sum_price_client
                -
                SUM(COALESCE(payment.sber_d, 0) + COALESCE(payment.sber_k, 0) + COALESCE(payment.privat, 0) + COALESCE(payment.um, 0) + COALESCE(payment.wmz, 0) + COALESCE(payment.birja, 0))
            ) as duty
        ")
            ->fromSub($reports, 'project')
            ->leftJoin('payment', function ($leftJoin) {
                $leftJoin->on('payment.project_id', '=', 'project.id')
                    ->where('payment.mark', true);
            })
            ->groupBy(['project.id']);

        $reports = Project::on()->select('projects.*')
            ->with(['projectStatus', 'projectStatusPayment', 'projectClients', 'projectUser:id,full_name'])
            ->fromSub($reports, 'projects');

        $this->filter($reports, $request);

        $reports = $reports->get();

        $statistics = [
            'duty' => $reports->sum('duty'),
            'sum_without_space' => $reports->sum('sum_without_space'),
            'sum_gross_income' => $reports->sum('sum_gross_income'),
            'profit' => $reports->sum('profit'),
            'middle_check' => $reports->sum('sum_price_client') == 0 ? 0 : $reports->sum('sum_price_client') / $reports->count(),
        ];

        $rates = Rate::on()->get();

        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get();

        $project = Project::on()->select(['id', 'project_name'])
            ->with(['projectAuthor', 'projectClients'])
            ->get()->toArray();

        $clients = Client::on()->get()->toArray();

        return view('report.client_report.client_report', [
            'reports' => $reports->toArray(),
            'statistics' => $statistics,
            'rates' => $rates,
            'statusPayments' => StatusPaymentProject::on()->get(),
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

        // долг
        if (!empty($request->duty_from) || !empty($request->duty_to)) {
            $reports->whereBetween('duty', [$request->duty_from ?? 0, $request->duty_to ?? 999999999]);
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
            $reports->whereHas('projectClients', function($where) use ($request){
                $where->where('clients.id', $request->client_id);
            });
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('report.client_report.client_project');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
