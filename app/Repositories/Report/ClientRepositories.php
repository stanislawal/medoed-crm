<?php

namespace App\Repositories\Report;

use App\Models\Article;
use App\Models\Payment\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Models\Project\Project;

class ClientRepositories
{

    /**
     * Возвращает отчет по клиентам
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReport($request)
    {

        $reports = Project::on()
            ->selectRaw("
                projects.id,
                projects.duty,
                projects.project_name,
                projects.manager_id,
                coalesce(SUM(articles.without_space), 0) as sum_without_space,
                SUM(
                    (COALESCE(articles.without_space, 0) * (COALESCE(articles.price_client, 0) / 1000))
                ) as sum_gross_income,
                ABS(datediff(
                    projects.start_date_project,
                    COALESCE(projects.end_date_project, CURRENT_DATE())
                )) as date_diff,
               coalesce(SUM((articles.price_client*(articles.without_space/1000))), 0) as sum_price_client,
               coalesce(SUM((articles.price_author *(articles.without_space/1000))), 0) as sum_price_author
        ")->from('projects')
            ->leftJoin('articles', function ($leftJoin) use ($request){
                $leftJoin->on('articles.project_id', '=', 'projects.id')
                    ->whereBetween('articles.created_at', [
                        Carbon::parse($request->month ?? now())->startOfMonth()->toDateTimeString(),
                        Carbon::parse($request->month ?? now())->endOfMonth()->toDateTimeString()
                    ]);
            })
            ->groupBy(['projects.id']);

        $reports = Project::on()
            ->selectRaw("
            project.*,
            (sum_without_space / date_diff) as symbol_in_day,
            (sum_price_client - sum_price_author) as profit,
            coalesce((
                sum_price_client
                -
                SUM(
                    COALESCE(payment.sber_a, 0) +
                    COALESCE(payment.tinkoff_a, 0) +
                    COALESCE(payment.sber_d, 0) +
                    COALESCE(payment.sber_k, 0) +
                    COALESCE(payment.privat, 0) +
                    COALESCE(payment.um, 0) +
                    COALESCE(payment.wmz, 0) +
                    COALESCE(payment.birja, 0)
                )
            ), 0) as finish_duty
        ")
            ->fromSub($reports, 'project')
            ->leftJoin('payment', function ($leftJoin) use ($request){
                $leftJoin->on('payment.project_id', '=', 'project.id')
                    ->where('payment.mark', 1)
                    ->whereBetween('payment.date', [
                        Carbon::parse($request->month ?? now())->startOfMonth()->toDateTimeString(),
                        Carbon::parse($request->month ?? now())->endOfMonth()->toDateString()
                    ]);
            })
            ->groupBy(['project.id']);

        $reports = Project::on()->selectRaw("
            projects.*,
            get_duty.remainder_duty
        ")
            ->with(['projectStatus', 'projectStatusPayment', 'projectClients', 'projectUser:id,full_name'])
            ->fromSub($reports, 'projects')
            ->leftJoinSub(self::getDuty(Carbon::parse($request->month)->toDateString()), 'get_duty', function($leftJoin){
                $leftJoin->on('get_duty.id', '=', 'projects.id');
            });

        return $reports;
    }

    public static function getByProject($id, $request)
    {
        $startDate = Carbon::parse($request->month ?? now())->startOfMonth()->toDateTimeString();
        $endDate = Carbon::parse($request->month ?? now())->endOfMonth()->toDateTimeString();
        $report = Article::on()->selectRaw("
              id,
              article as article_name,
              project_id,
              without_space,
              price_client,
              (without_space * ((price_client *(without_space/1000)) / 1000)) as gross_income,
              price_author,
              created_at
        ")->whereBetween('articles.created_at', [$startDate, $endDate]);

        $report = Article::on()->selectRaw("
                projects.project_name,
                articles.*,
                ((articles.price_client - articles.price_author) * (articles.without_space / 1000)) as margin,
                ((articles.without_space / 1000) * articles.price_client) as price_article
            ")
            ->from('projects')
            ->leftJoinSub($report, 'articles', 'articles.project_id', '=', 'projects.id')
            ->where('projects.id', $id)
            ->with(['articleAuthor:id,full_name']);
        return $report;
    }

    /**
     * Возвращает остаток долга клиента до указанной даты
     *
     * @param $date
     * @param $projectId
     * @return Builder
     */
    public static function getDuty($date, $projectId = null)
    {
        $date = Carbon::parse($date)->startOfMonth()->subDay()->toDateString();

        $projects = Project::on()
            ->selectRaw("
                projects.id,
                coalesce(SUM((articles.price_client*(articles.without_space/1000))), 0) as sum_price_client
        ")->from('projects')
            ->leftJoin('articles', function($leftJoin) use ($date){
                $leftJoin->on('articles.project_id', '=', 'projects.id')
                    ->whereRaw("articles.created_at <= '{$date}'");
            })
            ->when(!is_null($projectId), function ($where) use ($projectId) {
                $where->where('projects.id', $projectId);
            })
            ->groupBy(['projects.id']);

        $projects = Project::on()->selectRaw("
            projects.id,
            (
                projects.sum_price_client -
                coalesce(sum(payment.sber_a + payment.sber_d + payment.sber_k + payment.tinkoff_a + payment.privat + payment.um + payment.wmz + payment.birja), 0)
            ) as remainder_duty
        ")->fromSub($projects, 'projects')
            ->leftJoin('payment', function ($leftJoin) use ($date) {
                $leftJoin->on('payment.project_id', '=', 'projects.id')
                    ->where('mark', true)
                    ->whereRaw("payment.date <= '{$date}'");
            })
            ->groupBy(['projects.id']);

        return $projects;
    }
}
