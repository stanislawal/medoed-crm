<?php

namespace App\Repositories\Report;

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
            ->whereBetween('articles.created_at', [$startDate, $endDate])
            ->groupBy('articles.id');

        $authors = User::on()
            ->selectRaw("
                users.id,
                banks.name as bank,
                users.full_name,
                sum(articles.without_space_author) as without_space,
                sum(articles.price) as amount,
                sum(articles.price_article) as gross_income,
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
            ->when(!empty($request->sort), function(Builder $orderBy) use ($request){
                $orderBy->orderBy($request->sort, $request->direction);
            });

        return $authors;
    }
}
