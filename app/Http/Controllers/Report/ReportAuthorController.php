<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\AuthorPayment\AuthorPayment;
use App\Models\Bank;
use App\Models\Rate\Rate;
use App\Models\User;
use App\Repositories\Report\AuthorRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportAuthorController extends Controller
{

    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $diffInWeekdays = Carbon::parse($startDate)->diffInWeekdays(Carbon::parse($endDate)) + 1;
        $diffInCurrentDay = Carbon::parse($startDate)->diffInWeekdays(Carbon::parse(now())) + 1;

        $reports = AuthorRepositories::getReport($request, $startDate, $endDate,
            $diffInWeekdays)
            ->paginate(50);

        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();


        $indicators = AuthorRepositories::getReport($request, $startDate, $endDate, $diffInWeekdays);

        $indicators = User::on()->selectRaw("
            sum(authors.margin) as margin,
            sum(authors.without_space) as without_space,
            sum(authors.amount) as amount,
            sum(authors.gross_income) as gross_income,
            sum(authors.duty) as duty,
            sum(authors.payment_amount) as payment_amount
        ")->fromSub($indicators, 'authors')
            ->first()
            ->toArray();

        $remainderDuty = AuthorRepositories::getDuty(Carbon::parse($startDate)->subDay(), $request->author_id, $request)
            ->get()
            ->toArray();

        return view('report.author.author_list', [
            'rates'            => Rate::on()->get(),
            'reports'          => $reports,
            'indicators'       => $indicators,
            'diffInWeekdays'   => $diffInWeekdays,
            'diffInCurrentDay' => $diffInCurrentDay,
            'authors'          => $authors,
            'remainderDuty'    => collect($remainderDuty),
            'banks'            => Bank::on()->get(),
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
        [$startDate, $endDate] = $this->monthElseRange($request);

        $ignoreArticleList = AuthorRepositories::getIgnoreArticles($startDate, $endDate, $id)->get()->toArray();

        $articles = AuthorRepositories::getReportByAuthor($startDate, $endDate, $id)->paginate(50);

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
            ->get()
            ->first()
            ->toArray();

        $remainderDuty = AuthorRepositories::getDuty(Carbon::parse($startDate)->subDay(), $id)->first()->remainder_duty ?? 0;

        // получить историю оплат
        $paymentHistory = AuthorPayment::on()->where('author_id', $id)->whereBetween('date', [
            $startDate, $endDate
        ])->orderByDesc('id')->get()->toArray();

        // в показатели добавляем сумму оплат из истории оплат
        $indicators['payment_amount'] = $indicators['payment_amount'] + (collect($paymentHistory)->sum('amount'));
        $indicators['duty'] = $indicators['duty'] - (collect($paymentHistory)->sum('amount'));

        return view('report.author.author_item', [
            'articles'          => $articles,
            'user'              => $user,
            'indicators'        => $indicators,
            'remainderDuty'     => $remainderDuty,
            'ignoreArticleList' => $ignoreArticleList,
            'paymentHistory'    => $paymentHistory
        ]);
    }

    public function monthElseRange($request)
    {

        if (!empty($request->month)) {
            $startDate = Carbon::parse($request->month)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($request->month)->endOfMonth()->format('Y-m-d');
        } else {
            $startDate = Carbon::parse($request->start_date ?? now()->startOfMonth())->format('Y-m-d');
            $endDate = Carbon::parse($request->end_date ?? now()->endOfMonth())->format('Y-m-d');
        }

        return [$startDate, $endDate];
    }
}
