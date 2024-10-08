<?php

namespace App\Http\Controllers\Report;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Report\WorkloadRepositories;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WorkloadController extends Controller
{
    public function index(Request $request)
    {

        $managers = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->get();

        $dates = $this->gerDate();

        $report = WorkloadRepositories::getReport($request, $dates);

        if (UserHelper::isManager()) {
            $report->where('users.id', UserHelper::getUserId());
        }

        $report = $report->get()->toArray();

        $report = $this->calculation($report);

        if (isset(request()->sort)) {
            $report['data'] = $this->sort($report['data']);
        }

        return view('report.workload.workload_list', [
            'managers' => $managers,
            'report'   => $report
        ]);
    }

    private function calculation($report)
    {
        $report = collect($report);

        $dates = $this->gerDate();

        // получить спсиок всех менеджеров (будет использоваться в выводе столбцов)
        $uniqueManages[] = 'Дата';
        $uniqueManages = [
            'Дата',
            ...$report->pluck('full_name')->sort()->unique()->values()->toArray(),
            'Итого'
        ];

        // получить список уникальных дат, будет использоваться в выводе строк

        $uniqueDate = [
            'Итого',
            ...$this->getDateList($dates)
        ];
        // $uniqueDate = $uniqueDate->whereNotNull()->values()->toArray();

        $result = [];

        $item = [];

        foreach ($uniqueManages as $manager) {
            if ($manager != 'Дата') {
                $item[] = 'ЗБП';
                $item[] = 'ВД';
            } else {
                $item[] = '';
            }
        }

        $result[] = $item;

        foreach ($uniqueDate as $date) {

            $item = [];

            foreach ($uniqueManages as $manager) {

                if ($manager == 'Итого') {

                    if ($date == 'Итого') {
                        $item[] = (float)$report->sum('without_space');
                        $item[] = (float)$report->sum('gross_income');
                    } else {
                        $item[] = (float)$report->where('date', $date)->sum('without_space');
                        $item[] = (float)$report->where('date', $date)->sum('gross_income');
                    }

                } else if ($manager == 'Дата') {
                    $item[] = $date;
                } else {

                    if ($date == 'Итого') {
                        $item[] = (float)$report->where('full_name', $manager)->sum('without_space') ?? 0;
                        $item[] = (float)$report->where('full_name', $manager)->sum('gross_income') ?? 0;
                    } else {
                        $value = $report->where('full_name', $manager)->where('date', $date)->values();

                        $item[] = (float)($value[0]['without_space'] ?? 0);
                        $item[] = (float)($value[0]['gross_income'] ?? 0);
                    }

                }
            }
            $result[] = $item;
        }

        return [
            'headers'       => $uniqueManages,
            'data'          => $result,
            'without_space' => $this->round($report->sum('without_space'), 2),
            'gross_income'  => $this->round($report->sum('gross_income'), 2),
            'count_article' => $this->round($report->sum('count_articles'), 0),
        ];
    }

    private function round($num, $decimal)
    {
        return number_format($num, $decimal, '.', ' ');
    }

    private function getDateList($dates)
    {
        $period = CarbonPeriod::create($dates[0], $dates[1]);
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    private function gerDate()
    {
        if (request()->has('date_from')) {
            $startDate = Carbon::parse(request()->date_from)->startOfDay();
        } else {
            $startDate = Carbon::parse(now())->startOfMonth()->startOfDay();
        }

        if (request()->has('date_before')) {
            $endDate = Carbon::parse(request()->date_before)->endOfDay();
        } else {
            $endDate = Carbon::parse(now())->endOfMonth()->endOfDay();
        }

        return [
            $startDate,
            $endDate
        ];
    }

    private function sort($array)
    {
        $newArray = collect($array);

        $newArray->forget(0);
        $newArray->forget(1);

        if (request()->direction == 'asc') {
            $newArray = $newArray->sortBy(request()->sort);
        } else {
            $newArray = $newArray->sortByDesc(request()->sort);
        }

        $newArray->prepend($array[1]);
        $newArray->prepend($array[0]);

        return $newArray->toArray();
    }
}
