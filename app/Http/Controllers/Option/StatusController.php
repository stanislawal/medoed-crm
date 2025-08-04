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

    public function store(Request $request)
    {
        Status::on()->create([
            'name' => $request->add_new_status,
            'color' => $request->color
        ]);

        return redirect()->back()->with(['message' => 'Состояние успешно добавлено']);
    }


    public function destroy($id)
    {
        Status::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Состояние успешно удален']);
    }
}
