<?php

namespace App\Http\Controllers\Option;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{

    public function index()
    {
        return view('Option.add_option_status', [
            'statuses' => Status::on()->orderBy('id', "asc")->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }


    public function store(Request $request)
    {
        $status = [
            'name' => $request->add_new_status
        ];

        Status::on()->create($status);

        return redirect()->back()->with(['message' => 'Состояние успешно добавлено']);
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
        Status::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Состояние успешно удален']);
    }
}
