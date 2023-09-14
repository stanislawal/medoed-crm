<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Bank;
use App\Models\Rate\Rate;
use App\Models\User;
use App\Repositories\Report\RedactorRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportRedactorController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $diffInWeekdays = Carbon::parse($startDate)->diffInWeekdays(Carbon::parse($endDate)) + 1;
        $diffInCurrentDay = Carbon::parse($startDate)->diffInWeekdays(Carbon::parse(now())) + 1;

        $reports = RedactorRepositories::getReport($request, $startDate, $endDate,
            $diffInWeekdays)->paginate(50);

        $authors = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 3);
        })->get();

        $indicators = RedactorRepositories::getReport($request, $startDate, $endDate, $diffInWeekdays);
        $indicators = User::on()->selectRaw("
            sum(authors.without_space) as without_space,
            sum(authors.amount) as amount,
            sum(authors.duty) as duty
        ")->fromSub($indicators, 'authors')
            ->first()
            ->toArray();

        $remainderDuty = RedactorRepositories::getDuty(Carbon::parse($startDate)->subDay())->get()->toArray();

        return view('report.redactor.redactor_list',
        [
            'rates' => Rate::on()->get(),
            'banks' => Bank::on()->get(),
            'authors' => $authors,
            'reports' => $reports,
            'indicators' => $indicators,
            'diffInWeekdays' => $diffInWeekdays,
            'diffInCurrentDay' => $diffInCurrentDay,
            'remainderDuty' => collect($remainderDuty),
        ]);
    }

    /**
     * @param Request $request
     * @param $redactorId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, $redactorId)
    {
        [$startDate, $endDate] = $this->monthElseRange($request);

        $ignoreArticleList = RedactorRepositories::getIgnoreArticles($startDate, $endDate, $redactorId)->get()
            ->toArray();

        $articles = RedactorRepositories::getReportByRedactor($startDate, $endDate, $redactorId)
            ->paginate(50);

        $indicators = RedactorRepositories::getReportByRedactor($startDate, $endDate, $redactorId);
        $indicators = Article::on()->selectRaw("
            coalesce(sum(report.without_space), 0) as without_space,
            coalesce(sum(report.price), 0) as price,
            coalesce(sum(report.price_article), 0) as price_article,
            coalesce(sum(report.margin), 0) as margin,
            coalesce(sum(report.redactor_payment_amount), 0) as redactor_payment_amount,
            coalesce((sum(report.price) - sum(report.redactor_payment_amount)), 0) as duty
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
            ->where('users.id', $redactorId)
            ->get()
            ->first()
            ->toArray();

        $remainderDuty = RedactorRepositories::getDuty(Carbon::parse($startDate)->subDay(), $redactorId)->first()->remainder_duty ?? 0;

        return view('report.redactor.redactor_item', [
            'indicators' => $indicators,
            'ignoreArticleList' => $ignoreArticleList,
            'articles' => $articles,
            'user' => $user,
            'remainderDuty' => $remainderDuty,
        ]);
    }

    public function monthElseRange($request){

        if(!empty($request->month)){
            $startDate = Carbon::parse($request->month)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::parse($request->month)->endOfMonth()->format('Y-m-d');
        }else{
            $startDate = Carbon::parse($request->start_date ?? now()->startOfMonth())->format('Y-m-d');
            $endDate = Carbon::parse($request->end_date ?? now()->endOfMonth())->format('Y-m-d');
        }

        return [$startDate, $endDate];
    }
}
