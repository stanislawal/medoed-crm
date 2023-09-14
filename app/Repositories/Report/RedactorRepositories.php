<?php

namespace App\Repositories\Report;

use App\Helpers\UserHelper;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class RedactorRepositories
{
    public static function getReport($request, $startDate, $endDate, $diffInWeekdays)
    {
        $articles = Article::on()
            ->selectraw("
                articles.id,
                articles.created_at,
                projects.project_name,
                articles.article,
                articles.without_space,
                articles.without_space as without_space_all,
                articles.redactor_payment_amount,
                (articles.without_space * (articles.price_redactor/1000)) as price,
                (articles.without_space * (articles.price_client/1000)) as price_article,
                (
                    (articles.without_space * (articles.price_client/1000))
                    -
                    (articles.without_space * (articles.price_redactor/1000))
                ) as margin
            ")->from('articles')
            ->leftJoin('projects', 'projects.id', '=', 'articles.project_id')
            ->whereBetween('articles.created_at', [
                Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                Carbon::parse($endDate)->endOfDay()->toDateTimeString(),
            ])
            ->where('ignore', false)
            ->groupBy('articles.id');

        $redactors = User::on()
            ->selectRaw("
                users.id,
                users.working_day,
                banks.name as bank,
                users.full_name,
                sum(articles.without_space) as without_space,
                sum(articles.price) as amount,
                sum(articles.price_article) as gross_income,
                ((sum(articles.price) - sum(articles.redactor_payment_amount))) as duty,
                sum(articles.redactor_payment_amount) as redactor_payment_amount,
                sum(articles.margin) as margin,
                (sum(articles.price)/(sum(articles.without_space)/1000)) as avg_price,
                (sum(articles.without_space)/{$diffInWeekdays}) as avg_without_space_in_day
            ")
            ->from('users')
            ->leftJoin('banks', 'banks.id', '=', 'users.bank_id')
            ->leftJoin('cross_article_redactor as cross', 'cross.user_id', '=', 'users.id')
            ->leftJoinSub($articles, 'articles', 'articles.id', '=', 'cross.article_id')
            ->whereNotNull('cross.user_id')
            ->groupBy(['users.id']);

        // подзапрос для внедрения сортировки
        $redactors = User::on()
            ->fromSub($redactors, 'redactors')
            ->when(!empty($request->sort), function (Builder $orderBy) use ($request) {
                $orderBy->orderBy($request->sort, $request->direction);
            });
        return $redactors;
    }

    public static function getReportByRedactor($startDate, $endDate, $redactorId)
    {
        $articles = Article::on()
            ->selectraw("
                articles.id,
                articles.created_at,
                projects.project_name,
                articles.article,
                articles.without_space,
                articles.price_redactor,
                articles.price_client,
                articles.redactor_payment_amount,
                articles.redactor_payment_date,
                (articles.without_space * (articles.price_redactor / 1000)) as price,
                (articles.without_space * (articles.price_client / 1000)) as price_article,
                (
                    (articles.without_space * (articles.price_client/1000))
                    -
                    (articles.without_space * (articles.price_redactor/1000))
                ) as margin
            ")->from('articles')
            ->leftJoin('projects', 'projects.id', '=', 'articles.project_id')
            ->whereBetween('articles.created_at', [
                Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                Carbon::parse($endDate)->endOfDay()->toDateTimeString(),
            ])
            ->where('ignore', false)
            ->groupBy('articles.id');

        // сортируем статьи принадлежащие данному пользователю
        $articles = Article::on()->selectRaw("
                articles.*,
                cross.article_id
            ")->fromSub($articles, 'articles')
            ->leftJoin('cross_article_redactor as cross', 'cross.article_id', 'articles.id')
            ->where('cross.user_id', $redactorId)
            ->orderByDesc('articles.created_at');

        return $articles;
    }

    public static function getIgnoreArticles($startDate, $endDate, $userI)
    {
        return Article::on()
            ->selectRaw("
                articles.*,
                (articles.without_space * (articles.price_redactor/1000)) as price,
                (articles.without_space * (articles.price_client/1000)) as price_article,
                (
                    (articles.without_space * (articles.price_client/1000))
                    -
                    (articles.without_space * (articles.price_redactor/1000))
                    -
                    (articles.without_space * (articles.price_author/1000))
                ) as margin
            ")
            ->with(['articleProject'])
            ->whereHas('articleRedactor', function ($where) use ($userI) {
                $where->where('users.id', $userI);
            })
            ->where('ignore', true)
            ->orderByDesc('articles.created_at');
    }

    /**
     * Возвращает долг до указанной даты по авторам
     *
     * @param $date
     * @param $redactorId
     * @return mixed
     */
    public static function getDuty($date, $redactorId = null)
    {
        $dateTo = Carbon::parse($date)->endOfDay()->toDateString();

        $articles = Article::on()->selectRaw("
            id,
            ((without_space * (price_redactor/1000)) - coalesce(redactor_payment_amount, 0)) as duty_article
        ")->from('articles')
            ->whereNotNull('project_id')
            ->whereRaw("CAST(created_at as DATE) <= '{$dateTo}'")
            ->where('ignore', false)
            ->orderByDesc('created_at');

        $dutyBuRedactor = User::on()->selectRaw("
            users.id as redactor_id,
            coalesce(sum(articles.duty_article), 0) as remainder_duty
        ")->from('users')
            ->leftJoin('cross_article_redactor as cross', 'cross.user_id', '=', 'users.id')
            ->leftJoinSub($articles, 'articles', 'articles.id', '=', 'cross.article_id')
            ->whereNotNull('cross.user_id')
            ->groupBy(['users.id'])
            ->when(!is_null($redactorId), function ($query) use ($redactorId) {
                $query->where('users.id', $redactorId);
            });

        return $dutyBuRedactor;
    }
}
