<?php

namespace App\Http\Controllers\Lid;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Lid\Audit;
use App\Models\Lid\CallUp;
use App\Models\Lid\Lid;
use App\Models\Lid\LidSpecialistStatus;
use App\Models\Lid\LidStatus;
use App\Models\Lid\LocationDialogue;
use App\Models\Lid\Resource;
use App\Models\Lid\Service;
use App\Models\Lid\SpecialistTask;
use App\Models\User;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LidController extends Controller
{

    public const ADVERTISING_COMPANY = ['А', 'К', 'Д'];

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $lids = Lid::on()->with([
            'resource',
            'locationDialogue',
            'service',
            'callUp',
            'audit',
            'specialistTask',
            'specialistUser',
            'lidStatus',
            'createUser',
            'lidSpecialistStatus'
        ]);
        $this->filter($lids, $request);
        $lids->orderByDesc('date_receipt')
            ->orderByDesc('advertising_company')
            ->orderBy('write_lid');
        $lids = $lids->paginate(20);


        // аналитика
        $analytics = Lid::on()->selectRaw("
               distinct advertising_company,
               count(id) as count
        ")->groupBy('advertising_company');
        $this->filter($analytics, $request);
        $analytics = $analytics->get();

        return view('lid.index', [
            'lids'                => $lids,
            'analytics'           => $analytics,
            'advertisingCompany'  => self::ADVERTISING_COMPANY,
            'resources'           => Resource::on()->get(),
            'lidStatuses'         => LidStatus::on()->get(),
            'lidSpecialistStatus' => LidSpecialistStatus::on()->get(),
            'services'            => Service::on()->get(),
            'locationDialogues'   => LocationDialogue::on()->get(),
            'callUps'             => CallUp::on()->get(),
            'audits'              => Audit::on()->get(),
            'specialistTasks'     => SpecialistTask::on()->get(),
            'specialistUsers'     => User::on()->select(['id', 'full_name'])->whereIn('id', [1, 41])->get(),
        ]);
    }

    private function filter(&$lids, $request)
    {
        $lids->when(!empty($request->month), function ($where) use ($request) {
            $where->whereBetween('date_receipt', [
                Carbon::parse($request->month)->startOfMonth()->toDateTimeString(),
                Carbon::parse($request->month)->endOfMonth()->toDateTimeString(),

            ]);
        })
            ->when(!empty($request->specialist_user_id), function (Builder $where) use ($request) {
                if ($request->specialist_user_id == 'null') {
                    $where->whereNull('specialist_user_id');
                } else {
                    $where->where('specialist_user_id', $request->specialist_user_id);
                }
            });
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $attr = $request->validate([
            'advertising_company' => ['required', 'string', Rule::in(self::ADVERTISING_COMPANY)],
            'date_receipt'        => ['required', 'date'],
            'resource_id'         => ['required', 'integer'],
            'service_id'          => ['required', 'integer'],
            'name_link'           => ['required', 'string', 'max:500'],
            'lid_status_id'       => ['required', 'integer'],
            'state'               => ['nullable', 'string', 'max:500'],
        ]);

        $attr['create_user_id'] = UserHelper::getUserId();

        Lid::on()->create($attr);

        return redirect()->route('lid.index');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $attr = $request->validate([
            'advertising_company'      => ['nullable', 'string', Rule::in(self::ADVERTISING_COMPANY)],
            'date_receipt'             => ['nullable', 'date'],
            'resource_id'              => ['nullable', 'integer'],
            'name_link'                => ['nullable', 'string', 'max:500'],
            'lid_status_id'            => ['nullable', 'integer'],
            'state'                    => ['nullable', 'string', 'max:500'],
            'lid_specialist_status_id' => ['nullable', 'integer'],
            'state_specialist'         => ['nullable', 'string', 'max:500'],
            'location_dialogue_id'     => ['nullable', 'integer'],
            'link_lid'                 => ['nullable', 'string', 'max:500'],
            'service_id'               => ['nullable', 'integer'],
            'call_up_id'               => ['nullable', 'integer'],
            'result_call'              => ['nullable', 'string', 'max:500'],
            'date_time_call_up'        => ['nullable', 'string', 'max:100'],
            'audit_id'                 => ['nullable', 'integer'],
            'specialist_task_id'       => ['nullable', 'integer'],
            'transfer_date'            => ['nullable', 'date'],
            'date_acceptance'          => ['nullable', 'date'],
            'ready_date'               => ['nullable', 'date'],
            'specialist_user_id'       => ['nullable', 'integer'],
            'link_to_site'             => ['nullable', 'string', 'max:100'],
            'region'                   => ['nullable', 'string', 'max:100'],
            'price'                    => ['nullable', 'string', 'max:256'],
            'business_are'             => ['nullable', 'string', 'max:100']
        ]);

        $attr['write_lid'] = $request->write_lid == 1 ? 1 : 0;

        Lid::on()->where('id', $id)->update($attr);

        return redirect()->back()->with(['success' => 'Данные успешно обновлены.']);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxUpdate(Request $request, $id)
    {
        try {
            $attr = $request->validate([
                'advertising_company'      => ['nullable', 'string', Rule::in(self::ADVERTISING_COMPANY)],
                'date_receipt'             => ['nullable', 'date'],
                'resource_id'              => ['nullable', 'integer'],
                'name_link'                => ['nullable', 'string', 'max:500'],
                'lid_status_id'            => ['nullable', 'integer'],
                'state'                    => ['nullable', 'string', 'max:500'],
                'lid_specialist_status_id' => ['nullable', 'integer'],
                'state_specialist'         => ['nullable', 'string', 'max:500'],
                'location_dialogue_id'     => ['nullable', 'integer'],
                'link_lid'                 => ['nullable', 'string', 'max:500'],
                'service_id'               => ['nullable', 'integer'],
                'call_up_id'               => ['nullable', 'integer'],
                'result_call'              => ['nullable', 'string', 'max:500'],
                'date_time_call_up'        => ['nullable', 'string', 'max:100'],
                'audit_id'                 => ['nullable', 'integer'],
                'specialist_task_id'       => ['nullable', 'integer'],
                'transfer_date'            => ['nullable', 'date'],
                'date_acceptance'          => ['nullable', 'date'],
                'ready_date'               => ['nullable', 'date'],
                'specialist_user_id'       => ['nullable', 'integer'],
                'link_to_site'             => ['nullable', 'string', 'max:100'],
                'region'                   => ['nullable', 'string', 'max:100'],
                'price'                    => ['nullable', 'string', 'max:256'],
                'business_are'             => ['nullable', 'string', 'max:100']
            ]);

            $attr['write_lid'] = $request->write_lid == 1 ? 1 : 0;

            Lid::on()->where('id', $id)->update($attr);

            return response()->json([
                'result' => true
            ]);

        } catch (Exception $e) {
            return response()->json([
                'result'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (UserHelper::isAdmin()) {
            Lid::on()->where('id', $id)->delete();
            return redirect()->back()->with(['success' => "Лид успешно удален [ID: {$id}]"]);
        }

        abort(403);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByIdHtml(Request $request)
    {
        $lid = Lid::on()->find($request->id);

        if (is_null($lid)) {
            return response()->json([
                'result'  => false,
                'message' => "Лид не найден"
            ]);
        }

        return response()->json([
            'result' => true,
            'html'   => view('Render.Lid.edit', [
                'lid'                 => $lid,
                'advertisingCompany'  => self::ADVERTISING_COMPANY,
                'resources'           => Resource::on()->get(),
                'locationDialogues'   => LocationDialogue::on()->get(),
                'services'            => Service::on()->get(),
                'callUps'             => CallUp::on()->get(),
                'audits'              => Audit::on()->get(),
                'specialistTasks'     => SpecialistTask::on()->get(),
                'specialistUsers'     => User::on()->select(['id', 'full_name'])->whereIn('id', [1, 41])->get(),
                'lidStatuses'         => LidStatus::on()->get(),
                'lidSpecialistStatus' => LidSpecialistStatus::on()->get(),
            ])->render()
        ]);
    }
}
