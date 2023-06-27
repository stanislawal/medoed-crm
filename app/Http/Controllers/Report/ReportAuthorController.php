<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Rate\Rate;
use Illuminate\Http\Request;

class ReportAuthorController extends Controller
{

    public function index()
    {
        $rates = Rate::on()->get();

        return view('report.author_report.author_report', [
            'rates' => $rates
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
