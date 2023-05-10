<?php

namespace App\Http\Controllers\Rate;

use App\Http\Controllers\Controller;
use App\Models\Rate\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{

    public function index()
    {
        $rates = Rate::on()->get();

        return view('currency.currency', [
            'rates' => $rates,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $usdId = 2;
        $eurId = 3;
        $uahId = 4;

        if(!empty($request->usd)){
            Rate::on()->updateOrCreate([
                'id_currency' => $usdId,
            ], [
                'id_currency' => $usdId,
                'rate' => $request->usd
            ]);
        }


        if(!empty($request->eur)) {
            Rate::on()->updateOrCreate([
                'id_currency' => $eurId,
            ], [
                'id_currency' => $eurId,
                'rate' => $request->eur
            ]);
        }

        if(!empty($request->uah)) {
            Rate::on()->updateOrCreate([
                'id_currency' => $uahId,
            ], [
                'id_currency' => $uahId,
                'rate' => $request->uah
            ]);
        }

        return redirect()->back();
    }
}
