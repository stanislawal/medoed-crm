<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Service\ServiceType;
use App\Models\Service\SpecialistService;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceType = ServiceType::on()->with('services')->get();
        return view('project_service.service_type.index', [
            'serviceType' => $serviceType
        ]);
    }

    public function store(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|unique:specialist_services',
            'color' => 'nullable|string',
        ]);
        ServiceType::on()->create($attr);
        return redirect()->back();
    }

    public function destroy($id)
    {
        ServiceType::on()->find($id)->delete();
        return redirect()->back();
    }
}
