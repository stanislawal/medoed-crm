<?php

namespace App\Http\Controllers\Lid;

use App\Constants\NotificationTypeConstants;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
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
    private NotificationController $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

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

        if ($request->has('sort')) {
            // sort
            if (str_contains($request->sort, '|')) {
                $parts = explode('|', $request->sort);

                $orderBy = implode('.', $parts);

                $lids->orderByRaw($orderBy . ' ' . $request->direction ?? 'asc');

            } else {
                $lids->when(!empty($request->sort), function ($orderBy) use ($request) { // use ($request) - это то самое замыкание, о котормо я тебе говорил)))
                    $orderBy->orderBy($request->sort, $request->direction ?? 'asc');
                });
            }
        } else {
            $lids->orderByDesc('date_receipt')
                ->orderByDesc('advertising_company')
                ->orderBy('write_lid')
                ->orderByDesc('id');
        }

//        dd($lids->toSql());

        $lids = $lids->paginate(20);

        // аналитика
        $analytics = Lid::on()->selectRaw("
               distinct advertising_company,
               count(id) as count
        ")
            ->when(!empty($request->month), function ($where) use ($request) {
                $where->whereBetween('date_receipt', [
                    Carbon::parse($request->month)->startOfMonth()->toDateTimeString(),
                    Carbon::parse($request->month)->endOfMonth()->toDateTimeString(),

                ]);
            })
            ->groupBy('advertising_company')
            ->get();

        // аналитика за текущий месяц
        $analyticsCurrentMonth = Lid::on()->selectRaw("
               distinct advertising_company,
               count(id) as count
        ")->groupBy('advertising_company')
            ->whereBetween('date_receipt', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->get();

        return view('lid.index', [
            'lids'                  => $lids,
            'analytics'             => $analytics,
            'analyticsCurrentMonth' => $analyticsCurrentMonth,
            'advertisingCompany'    => self::ADVERTISING_COMPANY,
            'resources'             => Resource::on()->get(),
            'lidStatuses'           => LidStatus::on()->orderBy('order')->get(),
            'lidSpecialistStatus'   => LidSpecialistStatus::on()->get(),
            'services'              => Service::on()->get(),
            'locationDialogues'     => LocationDialogue::on()->get(),
            'callUps'               => CallUp::on()->get(),
            'audits'                => Audit::on()->get(),
            'specialistTasks'       => SpecialistTask::on()->get(),
            'specialistUsers'       => User::on()->select(['id', 'full_name'])->whereIn('id', [1, 41])->get(),
        ]);
    }

    /**
     * @param $lids
     * @param $request
     * @return void
     */
    private function filter(&$lids, $request)
    {

//        dd($request->all());

        $lids
            // месяц
            ->when(!empty($request->month), function ($where) use ($request) {
                $where->whereBetween('date_receipt', [
                    Carbon::parse($request->month)->startOfMonth()->toDateTimeString(),
                    Carbon::parse($request->month)->endOfMonth()->toDateTimeString(),
                ]);
            })
            // id
            ->when(!empty($request->id), function (Builder $where) use ($request) {
                $where->where('id', $request->id);
            })
            // специалист
            ->when(!empty($request->specialist_user_id), function (Builder $where) use ($request) {
                if ($request->specialist_user_id == 'null') {
                    $where->whereNull('specialist_user_id');
                } else {
                    $where->where('specialist_user_id', $request->specialist_user_id);
                }
            })
            // рекламная компания
            ->when(!empty($request->advertising_company), function (Builder $where) use ($request) {
                $where->where('advertising_company', $request->advertising_company);
            })
            // имя ссылка
            ->when(!empty($request->name_link), function (Builder $where) use ($request) {

                $where->whereRaw("name_link like '%{$request->name_link}%'")
                    ->orWhereRaw("link_lid like '%{$request->name_link}%'");
            })
            // статусы
            ->when(!empty($request->lid_status_id), function (Builder $where) use ($request) {
                $where->whereIn('lid_status_id', $request->lid_status_id);
            })
            // статусы исключение
            ->when(!empty($request->without_lid_status_id), function (Builder $where) use ($request) {
                $where->whereNotIn('lid_status_id', $request->without_lid_status_id);
            })
            // ресурс
            ->when(!empty($request->resource_id), function (Builder $where) use ($request) {
                $where->whereIn('resource_id', $request->resource_id);
            })
            // услуги
            ->when(!empty($request->service_id), function (Builder $where) use ($request) {
                $where->whereIn('service_id', $request->service_id);
            })
            // диапазон дат
            ->when(!empty($request->date_from) && !empty($request->date_before), function (Builder $where) use ($request) {
                $where->whereBetween('date_receipt', [
                    Carbon::parse($request->date_from)->startOfDay()->toDateTimeString(),
                    Carbon::parse($request->date_before)->endOfDay()->toDateTimeString(),
                ]);
            })
            // аудит
            ->when(!empty($request->audit_id), function (Builder $where) use ($request) {
                $where->whereIn('audit_id', $request->audit_id);
            })
            // Статус специалиста
            ->when(!empty($request->lid_specialist_status_id), function (Builder $where) use ($request) {
                $where->whereIn('lid_specialist_status_id', $request->lid_specialist_status_id);
            })
            // Исключить статус специалиста
            ->when(!empty($request->without_lid_specialist_status_id), function (Builder $where) use ($request) {
                $where->whereNotIn('lid_specialist_status_id', $request->without_lid_specialist_status_id);
            })
            // созвон
            ->when(!empty($request->call_up_id), function (Builder $where) use ($request) {
                $where->whereIn('call_up_id', $request->call_up_id);
            })
            // задача специалиста
            ->when(!empty($request->specialist_task_id), function (Builder $where) use ($request) {
                $where->whereIn('specialist_task_id', $request->specialist_task_id);
            })
            // Дата прописки лиду
            ->when(!empty($request->date_write_lid), function (Builder $where) use ($request) {
                $where->where('date_write_lid', now()->toDateString());
            });
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $attr = $request->validate([
            'advertising_company'  => ['required', 'string', Rule::in(self::ADVERTISING_COMPANY)],
            'date_receipt'         => ['required', 'date'],
            'resource_id'          => ['required', 'integer'],
            'service_id'           => ['required', 'integer'],
            'name_link'            => ['required', 'string', 'max:500'],
            'lid_status_id'        => ['required', 'integer'],
            'state'                => ['nullable', 'string', 'max:500'],
            'location_dialogue_id' => ['nullable', 'integer'],
            'link_lid'             => ['nullable', 'string', 'max:500']
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

        $this->auditCheck($request, $attr);

        $oldLid = Lid::on()->find($id);
        Lid::on()->where('id', $id)->update($attr);

        if (!empty($attr['lid_status_id']) && ($oldLid->lid_status_id != $attr['lid_status_id']) && in_array($attr['lid_status_id'], [4, 13, 3, 2, 5])) {
            $lidStatus = LidStatus::on()->find($attr['lid_status_id']);
            $message = 'Изменение статуса на:
                    <span class="select-2-custom-state-color nowrap px-1" style="background-color: ' . ($lidStatus->color ?? '#c7c7c7') . '">
                       ' . $lidStatus->name . '
                    </span> ';
            $this->notification($id, $message);
        }

        if (!empty($attr['lid_specialist_status_id']) && ($oldLid->lid_specialist_status_id != $attr['lid_specialist_status_id']) && in_array($attr['lid_specialist_status_id'], [])) {
            $lidSpecialistStatus = LidSpecialistStatus::on()->find($attr['lid_specialist_status_id']);
            $message = 'Изменение статуса специалиста на:
                    <span class="select-2-custom-state-color nowrap px-1" style="background-color: ' . ($lidSpecialistStatus->color ?? '#c7c7c7') . ' ">
                        ' . $lidSpecialistStatus->name . '
                    </span>';
            $this->notification($id, $message);
        }

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
                'business_are'             => ['nullable', 'string', 'max:100'],
                'date_write_lid'           => ['nullable', 'date']
            ]);

            $this->auditCheck($request, $attr);

            if ($request->has('write_lid')) {
                $attr['write_lid'] = $request->write_lid == 1 ? 1 : 0;
            }

            if ($request->has('interesting')) {
                $attr['interesting'] = $request->interesting == 1 ? 1 : 0;
            }

            $oldLid = Lid::on()->find($id);
            Lid::on()->where('id', $id)->update($attr);

            if (!empty($attr['lid_status_id']) && ($oldLid->lid_status_id != $attr['lid_status_id']) && in_array($attr['lid_status_id'], [4, 13, 3, 2, 5])) {
                $lidStatus = LidStatus::on()->find($attr['lid_status_id']);
                $message = 'Изменение статуса на:
                    <span class="select-2-custom-state-color nowrap px-1" style="background-color: ' . ($lidStatus->color ?? '#c7c7c7') . '">
                       ' . $lidStatus->name . '
                    </span> ';
                $this->notification($id, $message);
            }

            if (!empty($attr['lid_specialist_status_id']) && ($oldLid->lid_specialist_status_id != $attr['lid_specialist_status_id']) && in_array($attr['lid_specialist_status_id'], [])) {
                $lidSpecialistStatus = LidSpecialistStatus::on()->find($attr['lid_specialist_status_id']);
                $message = 'Изменение статуса специалиста на:
                    <span class="select-2-custom-state-color nowrap px-1" style="background-color: ' . ($lidSpecialistStatus->color ?? '#c7c7c7') . ' ">
                        ' . $lidSpecialistStatus->name . '
                    </span>';
                $this->notification($id, $message);
            }

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

    /**
     * @param $request
     * @param $attr
     * @return void
     */
    private function auditCheck($request, &$attr)
    {
        if ($request->has('audit_id')) {
            switch ($request['audit_id']) {
                case null :
                    $attr['transfer_date'] = $attr['transfer_date'] ?? null;
                    $attr['date_acceptance'] = $attr['date_acceptance'] ?? null;
                    $attr['ready_date'] = $attr['ready_date'] ?? null;
                    break;

                case 1 :
                    $attr['transfer_date'] = $attr['transfer_date'] ?? now();
                    break;

                case 2 :
                    $attr['date_acceptance'] = $attr['date_acceptance'] ?? now();
                    break;

                case 3 || 4 || 5 :
                    $attr['ready_date'] = $attr['ready_date'] ?? now();
                    break;

                default:
                    break;
            }
        }
    }

    private function notification($lidId, $message)
    {
        $this->notificationController->createNotification(
            NotificationTypeConstants::UPDATE_STATUS_LID,
            '',
            '',
            $message,
            UserHelper::getUserId(),
            $lidId
        );
    }
}
