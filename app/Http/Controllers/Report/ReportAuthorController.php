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
            sum(authors.gross_income) as gross_income,
            sum(authors.duty) as duty
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

        $articles = AuthorRepositories::getReportByAuthor($startDate, $endDate, $id)
            ->paginate(20);

        $indicators = AuthorRepositories::getReportByAuthor($startDate, $endDate, $id);

        $indicators = Article::on()->selectRaw("
            sum(report.without_space_author) as without_space_author,
            sum(report.price) as price,
            sum(report.price_article) as price_article,
            sum(report.margin) as margin,
            sum(report.payment_amount) as payment_amount,
            (sum(report.price) - sum(report.payment_amount)) as duty
        ")->fromSub($indicators, 'report')
            ->first()
            ->toArray();

        $user = User::on()
            ->selectRaw("
                users.id,
                users.full_name,
                users.payment,
                banks.name as bank,
                users.duty
            ")
            ->from('users')
            ->leftJoin('banks', 'banks.id', '=', 'users.bank_id')
            ->where('users.id', $id)
            ->get()->first()->toArray();

        return view('report.author.item', [
            'articles' => $articles,
            'user' => $user,
            'indicators' => $indicators
        ]);
    }

}
