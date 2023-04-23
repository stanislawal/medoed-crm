<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use Illuminate\Http\Request;

class ReportClientController extends Controller
{

    public function index()
    {
        $reports = Project::on()
            ->selectRaw("
                projects.*,
                ('777') as duty,
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
            ->groupBy(['projects.id', 'duty']);


        $reports = Project::on()
            ->with(['projectStatus', 'projectClients', 'projectUser:id,full_name'])
            ->selectRaw("
            t.*,
            (sum_without_space / date_diff) as symbol_in_day,
            (sum_price_client - sum_price_author) as profit
        ")
            ->fromSub($reports, 't')
            ->get();

        $statistics = [
            'duty' => $reports->sum('duty'),
            'sum_without_space' => $reports->sum('sum_without_space'),
            'sum_gross_income' => $reports->sum('sum_gross_income'),
            'profit' => $reports->sum('profit'),
            'middle_check' => "-",
        ];

        return view('report.client_report.client_report', [
            'reports' => $reports->toArray(),
            'statistics' => $statistics
        ]);
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
    public function show($id)
    {
        //
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
