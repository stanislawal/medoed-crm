<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectService\CreateRequest;
use App\Models\Project\Project;
use App\Models\Service\Service;
use App\Models\Service\ServiceType;
use App\Models\Service\SpecialistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectServiceController extends Controller
{
    public function index()
    {
        $projectServices = Service::on()->with([
            'project',
            'serviceType',
            'specialists'
        ])
            ->paginate(20);

        return view('project_service.service_index', [
            'projectServices' => $projectServices,
            'projects'        => Project::on()->select(['id', 'project_name'])->get(),
            'service_type'    => ServiceType::on()->select(['id', 'name'])->get(),
            'specialists'     => SpecialistService::on()->select(['id', 'name'])->get(),
        ]);
    }

    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $attr = collect($request->validated());

            $service = Service::on()->create(
                $attr->except('specialist_service_id')->toArray()
            );

            $service->specialists()->sync($attr['specialist_service_id']);

            DB::commit();

            return redirect()->back()->with(['success' => 'Услуга успешно создана']);

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $attr = collect($request->validate([
                'project_id'            => 'nullable|integer',
                'service_type_id'       => 'nullable|integer',
                'project_theme'         => 'nullable|string',
                'reporting_data'        => 'nullable|date',
                'terms_payment'         => 'nullable|string',
                'region'                => 'nullable|string',
                'all_price'             => 'nullable|numeric',
                'accrual_this_month'    => 'nullable|numeric',
                'task'                  => 'nullable|string',
                'specialist_service_id' => 'nullable|array',
                'link_to_work_plan'     => 'nullable|string',
            ]));

            $service = Service::on()->find($id);

            $service->update(
                $attr->except('specialist_service_id')->toArray()
            );

            if (!empty($attr['specialist_service_id'])) {
                $service->specialists()->sync($attr['specialist_service_id']);
            }

            DB::commit();
            return response()->json(['result' => true]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'result'  => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $service = Service::on()->find($id);

            $service->specialists()->detach();

            $service->delete();

            DB::commit();

            return redirect()->back()->with(['success' => 'Услуга удалена']);

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }

    }
}
