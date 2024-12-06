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
    public static function getReport($request, $startDate, $endDate)
    {

        $reports = Project::on()
            ->selectRaw("
                projects.id,
                projects.duty,
                projects.project_name,
                projects.manager_id,
                projects.theme_id,
                projects.style_id,
                projects.status_payment_id,
                projects.payment_terms,
                projects.requisite_id,
                requisites.name as requisite,
                coalesce(SUM(articles.without_space), 0) as sum_without_space,

                SUM(
                    CASE
                        WHEN articles.is_fixed_price_client = 1
                        THEN articles.price_client
                        ELSE (articles.price_client * (articles.without_space / 1000))
                    END
                ) as sum_gross_income,

                ABS(datediff(
                    projects.start_date_project,
                    COALESCE(projects.end_date_project, CURRENT_DATE())
                )) as date_diff,

               coalesce(
                   SUM(
                       CASE
                           WHEN articles.is_fixed_price_client = 1
                           THEN articles.price_client
                           ELSE (articles.price_client * (articles.without_space / 1000))
                       END
                   ),
               0) as sum_price_client,

               coalesce(
                   SUM(
                       CASE
                           WHEN articles.is_fixed_price_author = 1
                           THEN articles.price_author
                           ELSE (articles.price_author * (articles.without_space / 1000))
                       END
                   ),
               0) as sum_price_author,

               coalesce(
                   SUM(
                       CASE
                           WHEN articles.is_fixed_price_redactor = 1
                           THEN articles.price_redactor
                           ELSE (articles.price_redactor * (articles.without_space / 1000))
                       END
                   ),
               0) as sum_price_redactor,

               coalesce(
                   (
                        SUM(
                            CASE
                                WHEN articles.is_fixed_price_client = 1
                                THEN articles.price_client
                                ELSE (articles.price_client * (articles.without_space / 1000))
                            END
                            -
                            CASE
                                WHEN articles.is_fixed_price_author = 1
                                THEN articles.price_author
                                ELSE (articles.price_author * (articles.without_space / 1000))
                            END
                       ) / COUNT(articles.id)
                   ),
               0) as diff_price

        ")->from('projects')
            ->leftJoin('articles', function ($leftJoin) use ($startDate, $endDate) {
                $leftJoin->on('articles.project_id', '=', 'projects.id')
                    ->whereBetween('articles.created_at', [
                        Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                        Carbon::parse($endDate)->endOfDay()->toDateTimeString()
                    ])
                    ->where('articles.ignore', false);
            })
            ->leftJoin('requisites', 'requisites.id', '=', 'projects.requisite_id')
            ->groupBy(['projects.id']);

        $reports = Project::on()
            ->selectRaw("
            project.*,
            (sum_without_space / date_diff) as symbol_in_day,
            (sum_price_client - sum_price_author - sum_price_redactor) as profit,
            coalesce((
                sum_price_client
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
            ), 0) as finish_duty
        ")
            ->fromSub($reports, 'project')
            ->leftJoin('payment', function ($leftJoin) use ($startDate, $endDate) {
                $leftJoin->on('payment.project_id', '=', 'project.id')
                    ->where('payment.mark', 1)
                    ->whereBetween('payment.date', [
                        Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                        Carbon::parse($endDate)->endOfDay()->toDateTimeString()
                    ]);
            })
            ->groupBy(['project.id']);

        $countPayment = Payment::on()->selectRaw("project_id, count(id) as count_payment")
            ->where('payment.mark', 1)
            ->where('date', '>', now()->subDays(13))
            ->where('status_payment_id', '!=', 12)
            ->groupBy(['project_id']);

        $reports = Project::on()->selectRaw("
            projects.*,
            get_duty.remainder_duty,
            cast((projects.finish_duty + get_duty.remainder_duty + projects.duty) as decimal(12,3)) as all_sum_duty,
            coalesce(countPayment.count_payment, 0) as count_payment
        ")
            ->fromSub($reports, 'projects')
            ->leftJoinSub(self::getDuty(Carbon::parse($startDate)->subDay()->toDateString()), 'get_duty', function ($leftJoin) {
                $leftJoin->on('get_duty.id', '=', 'projects.id');
            })
            ->leftJoinSub($countPayment, 'countPayment', 'projects.id', '=', 'countPayment.project_id');

        $reports = Project::on()->selectRaw("
            projects.*,
            (
                projects.finish_duty + coalesce(projects.remainder_duty, 0) + coalesce(projects.duty, 0)
            ) as duty_for_sort
        ")
            ->fromSub($reports, 'projects')
            ->with([
                'projectStatus',
                'projectStatusPayment',
                'projectClients',
                'projectUser:id,full_name',
                'projectTheme:id,name',
                'projectStyle:id,name'
            ])
            ->orderByDesc('id');

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
              is_fixed_price_client,
              is_fixed_price_author,
              (without_space * ((price_client * ( without_space / 1000)) / 1000)) as gross_income,
              price_author,
              created_at
        ")
            ->whereBetween('articles.created_at', [$startDate, $endDate])
            ->where('articles.ignore', false);

        $report = Article::on()->selectRaw("
                projects.project_name,
                articles.*,
                (
                    CASE
                        WHEN articles.is_fixed_price_client = 1
                        THEN articles.price_client
                        ELSE (articles.price_client * (articles.without_space / 1000))
                    END
                    -
                    CASE
                        WHEN articles.is_fixed_price_author = 1
                        THEN articles.price_author
                        ELSE (articles.price_author * (articles.without_space / 1000))
                    END
                ) as margin,

               CASE
                   WHEN articles.is_fixed_price_client = 1
                   THEN articles.price_client
                   ELSE (articles.price_client * (articles.without_space / 1000))
               END as price_article,

               (
                    CASE
                        WHEN articles.is_fixed_price_client = 1
                        THEN articles.price_client
                        ELSE (articles.price_client * (articles.without_space / 1000))
                    END
                    -
                    CASE
                        WHEN articles.is_fixed_price_author = 1
                        THEN articles.price_author
                        ELSE (articles.price_author * (articles.without_space / 1000))
                    END
               ) as diff_price
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
        $projects = Project::on()
            ->selectRaw("
                projects.id,
                coalesce(
                    SUM(
                        (
                            CASE
                                WHEN articles.is_fixed_price_client = 1
                                THEN articles.price_client
                                ELSE (articles.price_client * (articles.without_space / 1000))
                            END
                        )
                    ),
                0) as sum_price_client
        ")->from('projects')
            ->leftJoin('articles', function ($leftJoin) use ($date) {
                $leftJoin->on('articles.project_id', '=', 'projects.id')
                    ->whereRaw("CAST(articles.created_at as DATE) <= '{$date}'")
                    ->where('articles.ignore', false);
            })
            ->when(!is_null($projectId), function ($where) use ($projectId) {
                $where->where('projects.id', $projectId);
            })
            ->groupBy(['projects.id']);

        $projects = Project::on()->selectRaw("
            projects.id,
            (
                projects.sum_price_client -
                coalesce(sum(payment.sber_a + payment.sber_d + payment.sber_k + payment.tinkoff_a + payment.tinkoff_k + payment.privat + payment.um + payment.wmz + payment.birja), 0)
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
