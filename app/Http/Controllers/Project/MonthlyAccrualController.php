<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\MonthlyAccrual;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonthlyAccrualController extends Controller
{
    public function updateOrCreate(Request $request)
    {
        $date = Carbon::parse($request->date)->startOfMonth()->format('Y-m-d');

        MonthlyAccrual::on()->updateOrCreate([
            'date' => $date,
            'project_id' => $request->project_id
        ], [
            'amount' => $request->amount
        ]);

        return response()->json();
    }
}
