<?php

namespace App\Http\Controllers\Option;

use App\Http\Controllers\Controller;
use App\Models\StatusPaymentProject;
use Illuminate\Http\Request;

class StatusPaymentController extends Controller
{

    public function index()
    {
        return view('Option.add_option_status_payment', [
            'statuses' => StatusPaymentProject::on()->withExists('projects')->get()
        ]);
    }

    public function store(Request $request)
    {
        StatusPaymentProject::on()->create([
            'name' => $request->name,
            'color' => $request->color ?? '#ffffff'
        ]);
        return redirect()->back()->with(['message' => 'Состояние успешно добавлено']);
    }


    public function destroy($id)
    {
        StatusPaymentProject::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Состояние успешно удален']);
    }
}
