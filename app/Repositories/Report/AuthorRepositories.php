<?php

namespace App\Repositories\Report;

use App\Helpers\UserHelper;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class AuthorRepositories
{
    public static function getReport($request, $startDate, $endDate, $diffInWeekdays)
    {
        $articles = Article::on()
            ->selectraw("
                articles.id,
                articles.created_at,
                projects.project_name,
                articles.article,
                (articles.without_space/count(cross.id)) as without_space_author,
                articles.without_space as without_space_all,
                articles.price_author,
                articles.payment_amount,
                articles.price_client,
                (articles.without_space/count(cross.id)*(articles.price_author/1000)) as price,
                (articles.without_space/count(cross.id)*(articles.price_client/1000)) as price_article,
                (
                    ((articles.without_space/count(cross.id))*(articles.price_client/1000))
                    -
                    ((articles.without_space/count(cross.id))*(articles.price_author/1000))
                ) as margin,
                count(cross.id) as count_authors
            ")->from('articles')
            ->leftJoin('projects', 'projects.id', '=', 'articles.project_id')
            ->leftJoin('cross_article_authors as cross', 'cross.article_id', 'articles.id')
            ->whereBetween('articles.created_at', [
                Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                Carbon::parse($endDate)->endOfDay()->toDateTimeString(),
            ])
            ->where('ignore', false)
            ->groupBy('articles.id');

        $authors = User::on()
            ->selectRaw("
                users.id,
                users.is_work,
                users.working_day,
                banks.name as bank,
                banks.id as bank_id,
                users.full_name,
                sum(articles.without_space_author) as without_space,
                sum(articles.price) as amount,
                sum(articles.price_article) as gross_income,
                ((sum(articles.price) - sum(articles.payment_amount)) + users.duty) as duty,
                sum(articles.payment_amount) as payment_amount,
                sum(articles.margin) as margin,
                (sum(articles.price)/(sum(articles.without_space_author)/1000)) as avg_price,
                (sum(articles.without_space_author)/{$diffInWeekdays}) as avg_without_space_in_day
            ")
            ->from('users')
            ->leftJoin('banks', 'banks.id', '=', 'users.bank_id')
            ->leftJoin('cross_article_authors as cross', 'cross.user_id', '=', 'users.id')
            ->leftJoinSub($articles, 'articles', 'articles.id', '=', 'cross.article_id')
            ->whereHas('roles', function ($query) {
                $query->where('id', 3);
            })
            ->groupBy(['users.id']);

        // подзапрос для внедрения сортировки
        $authors = User::on()
            ->fromSub($authors, 'authors')
            ->when(!empty($request->sort), function (Builder $orderBy) use ($request) {
                $orderBy->orderBy($request->sort, $request->direction);
            })

            ->when(!empty($request->status_work), function (Builder $where) use ($request) {
                $where->where('authors.is_work', $request->status_work);
            })

            ->when(!empty($request->bank_id), function (Builder $where) use ($request) {
                $where->where('authors.bank_id', $request->bank_id);
            })

            ->when(!empty($request->author_id), function (Builder $where) use ($request) {
                $where->where('authors.id', $request->author_id);
            })
            ->when(UserHelper::isAuthor(), function (Builder $where) use ($request) {
                $where->where('authors.id', UserHelper::getUserId());
            });
        return $authors;
    }

    public static function getReportByAuthor($startDate, $endDate, $userId)
    {
        $articles = Article::on()
            ->selectraw("
                articles.id,
                articles.created_at,
                projects.project_name,
                articles.article,
                (articles.without_space/count(cross.id)) as without_space_author,
                articles.without_space as without_space_all,
                articles.price_author,
                articles.price_client,
                articles.payment_amount,
                articles.payment_date,
                (articles.without_space/count(cross.id)*(articles.price_author/1000)) as price,
                (articles.without_space/count(cross.id)*(articles.price_client/1000)) as price_article,
                (
                    ((articles.without_space/count(cross.id))*(articles.price_client/1000))
                    -
                    ((articles.without_space/count(cross.id))*(articles.price_author/1000))
                ) as margin,
                count(cross.id) as count_authors
            ")->from('articles')
            ->leftJoin('projects', 'projects.id', '=', 'articles.project_id')
            ->leftJoin('cross_article_authors as cross', 'cross.article_id', 'articles.id')
            ->whereBetween('articles.created_at', [
                Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                Carbon::parse($endDate)->endOfDay()->toDateTimeString(),
            ])
            ->where('ignore', false)
            ->groupBy('articles.id');

        // сортируем статьи принадлежащие данному пользователю
        $articles = Article::on()->selectRaw("articles.*, cross.article_id")->fromSub($articles, 'articles')
            ->leftJoin('cross_article_authors as cross', 'cross.article_id', 'articles.id')
            ->where('cross.user_id', $userId)
            ->orderByDesc('articles.created_at');

        return $articles;
    }

    public static function getIgnoreArticles($startDate, $endDate, $userI)
    {
        return Article::on()
            ->selectRaw("
                articles.*,
                (articles.without_space * (articles.price_author/1000)) as price,
                (articles.without_space * (articles.price_client/1000)) as price_article,
                (
                    (articles.without_space * (articles.price_client/1000))
                    -
                    (articles.without_space * (articles.price_author/1000))
                ) as margin
            ")
            ->with(['articleProject'])
            ->whereHas('articleAuthor', function ($where) use ($userI) {
                $where->where('users.id', $userI);
            })
            ->where('ignore', true)
            ->orderByDesc('articles.created_at');
    }

    /**
     * Возвращает долг до указанной даты по авторам
     *
     * @param $date
     * @param $authorId
     * @return mixed
     */
    public static function getDuty($date, $authorId = null)
    {
        $dateTo = Carbon::parse($date)->endOfDay()->toDateString();

        $articles = Article::on()->selectRaw("
            id,
            created_at,
            coalesce((without_space * (price_author/1000)), 0) as payment_author,
            coalesce(payment_amount, 0) as payment_amount,
            coalesce(((without_space * (price_author/1000)) - payment_amount), 0) as remainder_duty
        ")->from('articles')
            ->whereNotNull('project_id')
            ->whereRaw("CAST(created_at as DATE) <= '{$dateTo}'")
            ->where('ignore', false)
            ->orderByDesc('created_at');

        $dutyBuAuthor = User::on()->selectRaw("
            users.id as author_id,
            coalesce(sum(articles.remainder_duty), 0) as remainder_duty
        ")->from('users')
            ->leftJoin('cross_article_authors as cross', 'cross.user_id', '=', 'users.id')
            ->leftJoinSub($articles, 'articles', 'articles.id', '=', 'cross.article_id')
            ->groupBy(['users.id'])
            ->when(!is_null($authorId), function ($where) use ($authorId) {
                $where->where('users.id', $authorId);
            });

        return $dutyBuAuthor;
    }
}
