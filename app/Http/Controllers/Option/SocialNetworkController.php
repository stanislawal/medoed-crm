<?php

namespace App\Http\Controllers\Option;

use App\Http\Controllers\Controller;
use App\Models\Client\SocialNetwork;
use Illuminate\Http\Request;

class SocialNetworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('Option.add_option_socialnetwork', [
            'socialnetwork' => SocialNetwork::on()->with(['isUse'])->orderBy('id', "asc")->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSelect()
    {
        $socialnetworks = SocialNetwork::on()->get();
        return response()->json([
            'html' => view('Render.Socialnetwork.select_socialnetwork', ['socialnetworks' => $socialnetworks])->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $socialnetwork = [
            'name' => $request->add_new_socialnetwork
        ];

        SocialNetwork::on()->create($socialnetwork);

        return redirect()->back()->with(['message' => 'Социальная сеть успешно добавлена']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SocialNetwork::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Соц.сеть успешно удалена']);
    }
}
