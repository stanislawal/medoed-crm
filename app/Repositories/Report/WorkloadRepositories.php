<?php

namespace App\Repositories\Report;

use App\Models\User;
use Carbon\Carbon;

class WorkloadRepositories
{
    public static function getReport($request, $dates)
    {
        $report = User::on()->selectRaw("
            users.full_name,
            DATE(articles.created_at) as date,
            sum(ROUND(COALESCE(articles.without_space, 0), 2)) as without_space,

            sum(
                ROUND(
                CASE
                    WHEN articles.is_fixed_price_client = 1
                    THEN COALESCE(articles.price_client, 0)
                    ELSE COALESCE(articles.without_space, 0) * (COALESCE(articles.price_client, 0) / 1000)
                END
                , 2)
            ) as gross_income,

            count(articles.id) as count_articles,
            sum(COALESCE(articles.without_space, 0)) as sum_without_space
        ")
            ->from('users')
            ->leftJoin('articles', function ($q) use ($dates) {
                $q->on('articles.manager_id', '=', 'users.id')
                    ->whereBetween('articles.created_at', $dates)
                    ->where('articles.ignore', false);
            })
            ->whereHas('roles', function ($query) {
                $query->where('id', 2);
            })
            ->when(isset($request->manager_id), function ($q) use ($request) {
                $q->where('users.id', $request->manager_id);
            }, function ($q) {
                $q->where('users.is_work', true)
                    ->orWhere(function ($q) {
                        $q->where('users.is_work', false)
                            ->whereNotNull('articles.created_at');
                    });
            })
            ->groupByRaw("users.full_name, DATE(articles.created_at)");

        return $report;
    }
}
