<?php

namespace App\Http\Controllers\Service;


use App\Models\Service\SpecialistService;
use Illuminate\Http\Request;

class SpecialistController
{
    public function index()
    {
        $specialists = SpecialistService::on()->with('services')->get();
        return view('project_service.specialist.index', [
            'specialists' => $specialists
        ]);
    }

    public function store(Request $request)
    {
        $attr = $request->validate(['name' => 'required|string|unique:specialist_services']);
        SpecialistService::on()->create($attr);
        return redirect()->back();
    }

    public function destroy($id)
    {
        SpecialistService::on()->find($id)->delete();
        return redirect()->back();
    }
}
