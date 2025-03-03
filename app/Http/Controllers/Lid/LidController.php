<?php

namespace App\Http\Controllers\Lid;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Lid\Audit;
use App\Models\Lid\CallUp;
use App\Models\Lid\Lid;
use App\Models\Lid\LidStatus;
use App\Models\Lid\LocationDialogue;
use App\Models\Lid\Resource;
use App\Models\Lid\Service;
use App\Models\Lid\SpecialistTask;
use App\Models\User;
use Doctrine\DBAL\Driver\Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LidController extends Controller
{

    public const ADVERTISING_COMPANY = ['А', 'К', 'Д'];

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
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
            'createUser'
        ])
            ->orderByDesc('date_receipt')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('lid.index', [
            'lids'               => $lids,
            'advertisingCompany' => self::ADVERTISING_COMPANY,
            'resources'          => Resource::on()->get(),
            'lidStatuses'        => LidStatus::on()->get()
        ]);
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
            'advertising_company'  => ['required', 'string', Rule::in(self::ADVERTISING_COMPANY)],
            'date_receipt'         => ['required', 'date'],
            'resource_id'          => ['required', 'integer'],
            'name_link'            => ['required', 'string', 'max:500'],
            'lid_status_id'        => ['required', 'integer'],
            'state'                => ['nullable', 'string', 'max:500'],
            'location_dialogue_id' => ['nullable', 'integer'],
            'link_lid'             => ['nullable', 'string', 'max:500'],
            'service_id'           => ['nullable', 'integer'],
            'call_up_id'           => ['nullable', 'integer'],
            'result_call'          => ['nullable', 'string', 'max:500'],
            'date_time_call_up'    => ['nullable', 'string', 'max:100'],
            'audit_id'             => ['nullable', 'integer'],
            'specialist_task_id'   => ['nullable', 'integer'],
            'transfer_date'        => ['nullable', 'date'],
            'date_acceptance'      => ['nullable', 'date'],
            'ready_date'           => ['nullable', 'date'],
            'specialist_user_id'   => ['nullable', 'integer'],
            'link_to_site'         => ['nullable', 'string', 'max:100'],
            'region'               => ['nullable', 'string', 'max:100'],
            'price'                => ['nullable', 'numeric'],
            'business_are'         => ['nullable', 'string', 'max:100']
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

    public function getByIdHtml(Request $request)
    {
        $lid = Lid::on()->with([
            'resource',
            'locationDialogue',
            'service',
            'callUp',
            'audit',
            'specialistTask',
            'specialistUser',
            'lidStatus',
            'createUser'
        ])->find($request->id);

        if (is_null($lid)) {
            return response()->json([
                'result'  => false,
                'message' => "Лид не найден"
            ]);
        }

        return response()->json([
            'result' => true,
            'html'   => view('Render.Lid.edit', [
                'lid'                => $lid,
                'advertisingCompany' => self::ADVERTISING_COMPANY,
                'resources'          => Resource::on()->get(),
                'locationDialogues'  => LocationDialogue::on()->get(),
                'services'           => Service::on()->get(),
                'callUps'            => CallUp::on()->get(),
                'audits'             => Audit::on()->get(),
                'specialistTasks'    => SpecialistTask::on()->get(),
                'specialistUsers'    => User::on()->select(['id', 'full_name'])->whereIn('id', [1, 41])->get(),
                'lidStatuses'        => LidStatus::on()->get()
            ])->render()
        ]);

//        return view('Render.Lid.edit', [
//            'lid' => $lid,
//            'advertisingCompany' => self::ADVERTISING_COMPANY,
//            'resources' => Resource::on()->get(),
//            'locationDialogues' => LocationDialogue::on()->get(),
//            'services' => Service::on()->get(),
//            'callUps' => CallUp::on()->get(),
//            'audits' => Audit::on()->get(),
//            'specialistTasks' => SpecialistTask::on()->get(),
//            'specialistUsers' => User::on()->select(['id', 'full_name'])->whereIn('id', [1, 41])->get(),
//            'lidStatuses' => LidStatus::on()->get()
//        ]);
    }
}
