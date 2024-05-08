<?php

namespace App\Repositories\Report;

use App\Helpers\UserHelper;
use App\Models\Article;
use App\Models\AuthorPayment\AuthorPayment;
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
                coalesce(if(users.is_work, users.working_day, 0), 0) as working_day,
                banks.name as bank,
                banks.id as bank_id,
                users.full_name,
                sum(articles.without_space_author) as without_space,
                sum(articles.price) as amount,
                sum(articles.price_article) as gross_income,
                ((sum(articles.price) - sum(articles.payment_amount)) + users.duty) as duty_tmp,
                sum(articles.payment_amount) as payment_amount_tmp,
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

        $payment = AuthorPayment::on()->selectRaw("
            author_id,
            sum(amount) as amount
        ")
            ->whereBetween('date', [
                Carbon::parse($startDate)->startOfDay()->toDateTimeString(),
                Carbon::parse($endDate)->endOfDay()->toDateTimeString(),
            ])
            ->groupBy('author_id');

        // подзапрос для внедрения сортировки
        $authors = User::on()->selectRaw("
            authors.*,
            (coalesce(authors.duty_tmp, 0) - coalesce(payment.amount, 0)) as duty,
            (coalesce(authors.payment_amount_tmp, 0) + coalesce(payment.amount, 0)) as payment_amount
        ")
            ->fromSub($authors, 'authors')
            ->leftJoinSub($payment, 'payment', 'payment.author_id', '=', 'authors.id')
            ->when(!empty($request->sort), function (Builder $orderBy) use ($request) {
                $orderBy->orderBy($request->sort, $request->direction);
            })
            ->when(!is_null($request->status_work), function (Builder $where) use ($request) {
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
    public static function getDuty($date, $authorId = null, $request = null)
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

        $dutyByAuthor = User::on()->selectRaw("
            users.id as author_id,
            coalesce(sum(articles.remainder_duty), 0) as remainder_duty
        ")->from('users')
            ->leftJoin('cross_article_authors as cross', 'cross.user_id', '=', 'users.id')
            ->leftJoinSub($articles, 'articles', 'articles.id', '=', 'cross.article_id')


            ->when(!is_null(($request->status_work ?? null)), function ($where) use ($request) {
                $where->where('users.is_work', $request->status_work);
            })
            ->groupBy(['users.id']);

        $payment = AuthorPayment::on()->selectRaw("
            author_id,
            sum(amount) as amount
        ")
            ->whereRaw("date <= '{$dateTo}'")
            ->groupBy('author_id');


        $dutyByAuthor = User::on()->selectRaw("
            duty.author_id,
            (duty.remainder_duty - coalesce(payment.amount, 0)) as remainder_duty
        ")->fromSub($dutyByAuthor, 'duty')
            ->leftJoinSub($payment, 'payment', 'duty.author_id', '=', 'payment.author_id')
            ->when(!is_null($authorId), function ($where) use ($authorId) {
                $where->where('duty.author_id', $authorId);
            });

        return $dutyByAuthor;
    }
}
