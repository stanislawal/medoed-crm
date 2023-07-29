<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Rate\Rate;
use App\Models\User;
use App\Repositories\Report\AuthorRepositories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportAuthorController extends Controller
{

    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->month ?? now())->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::parse($request->month ?? now())->endOfMonth()->format('Y-m-d');
        $diffInWeekdays = Carbon::parse($startDate)->diffInWeekdays(Carbon::parse($endDate)) + 1;

        $authors = AuthorRepositories::getReport($request, $startDate, $endDate, $diffInWeekdays)->paginate(50);

        $indicators = AuthorRepositories::getReport($request, $startDate, $endDate, $diffInWeekdays);

        $indicators = User::on()->selectRaw("
            sum(authors.margin) as margin,
            sum(authors.without_space) as without_space,
            sum(authors.amount) as amount,
            sum(authors.gross_income) as gross_income
        ")->fromSub($indicators, 'authors')
            ->first()
            ->toArray();

        return view('report.author.list', [
            'rates' => Rate::on()->get(),
            'authors' => $authors,
            'indicators' => $indicators,
            'diffInWeekdays' => $diffInWeekdays
        ]);
    }

    /**
     * Возвращает отчет по указанному автору
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, $id)
    {

        $startDate = Carbon::parse($request->month ?? now())->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::parse($request->month ?? now())->endOfMonth()->format('Y-m-d');

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


        // сортируем статьи принадлежащие данному пользователю
        $articles = Article::on()->fromSub($articles, 'articles')
            ->leftJoin('cross_article_authors as cross', 'cross.article_id', 'articles.id')
            ->where('cross.user_id', $id)->get()->toArray();

        $user = User::on()
            ->selectRaw("
                users.id,
                users.full_name,
                users.payment,
                banks.name as bank
            ")
            ->from('users')
            ->leftJoin('banks', 'banks.id', '=', 'users.bank_id')
            ->where('users.id', $id)
            ->get()->first()->toArray();

        return view('report.author.item', [
            'articles' => collect($articles),
            'user' => $user
        ]);
    }

}
