<?php

namespace App\Http\Controllers\Service;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectService\CreateRequest;
use App\Models\Project\Project;
use App\Models\Service\Service;
use App\Models\Service\ServiceType;
use App\Models\Service\SpecialistService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectServiceController extends Controller
{
    public function index(Request $request)
    {
        $projectServices = Service::on()->with([
            'project:id,project_name,project_theme_service,reporting_data,terms_payment,region,passport_to_work_plan',
            'serviceType',
            'specialists',
            'createdUser'
        ])
            ->orderByDesc('id');

        $this->filter($projectServices, $request);

        $projectServices = $projectServices->paginate(20);

        // получаем администраторов и менеджеров
        $creater = User::on()->whereHas('roles', function ($query) {
            $query->whereIn('id', [1, 2]);
        })
            ->where('is_work', true)
            ->get();

        return view('project_service.service_index', [
            'projectServices' => $projectServices,
            'projects'        => Project::on()->select(['id', 'project_name'])->get(),
            'service_type'    => ServiceType::on()->get(),
            'specialists'     => SpecialistService::on()->get(),
            'creater'         => $creater
        ]);
    }

    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $attr = collect($request->validated());

            $attr['user_id'] = UserHelper::getUserId();

            $service = Service::on()->create(
                $attr->except('specialist_service_id')->toArray()
            );

            if (!empty($attr['specialist_service_id'])) {
                $service->specialists()->sync($attr['specialist_service_id']);
            }

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
                'all_price'             => 'nullable|numeric',
                'accrual_this_month'    => 'nullable|numeric',
                'task'                  => 'nullable|string',
                'specialist_service_id' => 'nullable|array',
                'name'                  => 'nullable|string',
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

    private function filter(&$query, $request)
    {
        $query->when(!empty($request->id), function ($q) use ($request) {
            $q->where('id', $request->id);
        });

        $query->when(!empty($request->task), function ($q) use ($request) {
            $q->where('task', $request->task);
        });

        $query->when(!empty($request->user_id), function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });

        $query->when(!empty($request->project_id), function ($q) use ($request) {
            $q->where('project_id', $request->project_id);
        });

        $query->when(!empty($request->reporting_data), function ($q) use ($request) {
            $q->whereHas('project', function ($q) use ($request) {
                $q->where('reporting_data', $request->reporting_data);
            });
        });

        $query->when(!empty($request->service_type_id), function ($q) use ($request) {
            $q->where('service_type_id', $request->service_type_id);
        });

        $query->when(!empty($request->date_from) && !empty($request->date_before), function ($q) use ($request) {
            $q->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_before)->endOfDay(),
            ]);
        });
    }
}
