<?php

namespace App\Repositories\Report;

use App\Models\Project\Project;

class ClientRepositories
{

    /**
     * Возвращает отчет по клиентам
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReport(){
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
                SUM((articles.price_client*(articles.without_space/1000))) as sum_price_client,
                SUM((articles.price_author *(articles.without_space/1000))) as sum_price_author
        ")->from('projects')
            ->leftJoin('articles', 'articles.project_id', '=', 'projects.id')
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
            ->leftJoin('payment', function ($leftJoin) {
                $leftJoin->on('payment.project_id', '=', 'project.id')
                    ->where('payment.mark', true);
            })
            ->groupBy(['project.id']);

        $reports = Project::on()->select('projects.*')
            ->with(['projectStatus', 'projectStatusPayment', 'projectClients', 'projectUser:id,full_name'])
            ->fromSub($reports, 'projects');

        return $reports;
    }

}
