<?php

namespace App\Http\Controllers;


use App\Constants\NotificationTypeConstants;
use App\Events\PushNotification;
use App\Helpers\UserHelper;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? null;
        $viewed = $request->is_viewed ?? null;

        $notification = Notification::on()
            ->with(['projects:id,project_name,manager_id', 'articles:id,article', 'projects.projectUser:id,full_name', 'lid:id', 'user:id,full_name'])
            ->where('recipient_id', UserHelper::getUserId())
            ->when(!is_null($type), function ($where) use ($type) {
                $where->where('type', $type);
            })
            ->when(!is_null($viewed), function ($where) use ($viewed) {
                $where->where('is_viewed', ($viewed));
            })
            ->orderBy('is_viewed', 'asc')
            ->orderBy('date_time', 'desc')
            ->paginate(50);


        return view('notification.list', [
            'notifications' => $notification
        ]);
    }

    /**
     * Возвращает все уведомления для отображения в шапке
     *
     * @return mixed
     */
    public function getHtml()
    {
        $notifications = Notification::on()
            ->with(['projects:id,project_name,manager_id', 'articles:id,article', 'projects.projectUser:id,full_name'])
            ->where('recipient_id', UserHelper::getUserId())
            ->where('is_viewed', false)
            ->orderBy('date_time', 'desc')
            ->get();

        return response()->json([
            'result' => true,
            'html'   => view('Render.Notifications.notification_list', ['notifications' => $notifications])->render(),
            'count'  => count($notifications)
        ]);
    }

    /**
     * Пометить умведомление как просмотренное
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function browse($id, Request $request)
    {
        Notification::on()->where('id', $id)
            ->where('recipient_id', UserHelper::getUserId())
            ->update(['is_viewed' => 1]);

        if ($request->ajax()) {
            return response()->json(['result' => true]);
        }
        return redirect()->back();
    }

    /*
     * Прочитать все уведомления
     */
    public function browseAll()
    {
        Notification::on()->where('recipient_id', UserHelper::getUserId())->update(['is_viewed' => 1]);
        return redirect()->back();
    }

    /**
     * Прочитать все уведомления одного типа
     *
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function browseInType(Request $request, $type)
    {
        Notification::on()->where('type', $type)
            ->where('recipient_id', UserHelper::getUserId())
            ->update(['is_viewed' => 1]);

        if ($request->ajax()) {
            return response()->json(['result' => true]);
        }
        return redirect()->back();
    }

    /**
     * Создать уведомление
     *
     * @param $type
     * @param $recipients
     * @param $projectId
     * @param $message
     * @return void
     */
    public function createNotification($type, $recipients, $projectId, $message = null, $userId = null, $lidId = null)
    {
        switch ($type) {
            // назначение прокта на менеджера
            case NotificationTypeConstants::ASSIGNED_PROJECT :
                $this->assignedProject($recipients, $projectId);
                break;

            // редактирование статьи
            case NotificationTypeConstants::CHANGE_ARTICLE :
                $this->changeArticle($recipients, $projectId, $message);
                break;

            // редактирование цены в проекте
            case NotificationTypeConstants::CHANGE_PRICE_PROJECT :
                $this->changePriceProject($recipients, $projectId);
                break;

            // отписать клиенту через неделю
            case NotificationTypeConstants::WRITE_TO_CLIENT_WEEK :
                $this->writeToClient($recipients, $projectId, NotificationTypeConstants::WRITE_TO_CLIENT_WEEK);
                break;

            // отписать клиенту через месяц
            case NotificationTypeConstants::WRITE_TO_CLIENT_MONTH :
                $this->writeToClient($recipients, $projectId, NotificationTypeConstants::WRITE_TO_CLIENT_MONTH);
                break;

            // уведомление об необходимости оплатить по проекту
            case NotificationTypeConstants::PROJECT_PAYMENT :
                $this->projectPayment($recipients, $projectId, NotificationTypeConstants::PROJECT_PAYMENT);
                break;

            // уведомление о дате связи с клиентом
            case NotificationTypeConstants::DATE_CONTACT_WITH_CLIENT :
                $this->dateConnectWithClient($recipients, $projectId, NotificationTypeConstants::DATE_CONTACT_WITH_CLIENT);
                break;

            // уведомление обновления статуса лида
            case NotificationTypeConstants::UPDATE_STATUS_LID :
                $this->updateStatusLid($userId, $lidId, NotificationTypeConstants::UPDATE_STATUS_LID, $message);
                break;
        }

        event(new PushNotification());
    }

    /**
     * Уведомление о назначении проекта менеджеру
     *
     * @param $userId
     * @param $projectId
     * @return void
     */
    private function assignedProject($userId, $projectId)
    {
        $recipients = $this->getAllAdmin();
        $recipients[] = $userId;

        $notifications = [];
        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time'    => now(),
                'type'         => NotificationTypeConstants::ASSIGNED_PROJECT,
                'recipient_id' => $recipient,
                'project_id'   => $projectId,
                'article_id'   => null
            ];
        }

        if (count($notifications) > 0) {
            Notification::on()->insert($notifications);
        }
    }

    /**
     * Уведомление об изменении статьи
     *
     * @param $userId
     * @param $articleId
     * @return void
     */
    private function changeArticle($userId, $articleId, $message)
    {
        $recipients = $this->getAllAdmin();

        if ($userId != '')
            $recipients[] = $userId;

        $notifications = [];
        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time'    => now(),
                'type'         => NotificationTypeConstants::CHANGE_ARTICLE,
                'recipient_id' => $recipient,
                'message'      => $message,
                'project_id'   => null,
                'article_id'   => $articleId
            ];
        }

        if (count($notifications) > 0) {
            Notification::on()->insert($notifications);
        }
    }

    /**
     * Уведомление об изменении цены зака в проекте
     *
     * @param $userId
     * @param $projectId
     * @return void
     */
    private function changePriceProject($userId, $projectId)
    {
        $recipients = $this->getAllAdmin();

        if ($userId != '')
            $recipients[] = $userId;

        $notifications = [];
        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time'    => now(),
                'type'         => NotificationTypeConstants::CHANGE_PRICE_PROJECT,
                'recipient_id' => $recipient,
                'message'      => null,
                'project_id'   => $projectId,
                'article_id'   => null
            ];
        }

        if (count($notifications) > 0) {
            Notification::on()->insert($notifications);
        }
    }

    /**
     * Уведомление по проектам, где надо отписатьк лиентам через неделю/месяц в зависимости от полученного типа уведомления
     *
     * @param $userId
     * @param $projectId
     * @return void
     */
    private function writeToClient($userId, $projectId, $type)
    {
        $recipients = $this->getAllAdmin();

        if ($userId != '')
            $recipients[] = $userId;

        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time'    => now(),
                'type'         => $type,
                'recipient_id' => $recipient,
                'message'      => null,
                'project_id'   => $projectId,
                'article_id'   => null
            ];
        }

        if (count($notifications) > 0) {
            Notification::on()->insert($notifications);
        }
    }

    /**
     * Уведмоление об необходимости оплатить по проекту
     *
     * @param $userId
     * @param $projectId
     * @param $type
     * @return void
     */
    private function projectPayment($userId, $projectId, $type)
    {
        $recipients = $this->getAllAdmin();

        if ($userId != '')
            $recipients[] = $userId;

        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time'    => now(),
                'type'         => $type,
                'recipient_id' => $recipient,
                'message'      => null,
                'project_id'   => $projectId,
                'article_id'   => null
            ];
        }

        if (count($notifications) > 0) {
            Notification::on()->insert($notifications);
        }
    }

    /**
     * Дата связи с клиентом
     *
     * @param $userId
     * @param $projectId
     * @param $type
     * @return void
     */
    private function dateConnectWithClient($userId, $projectId, $type)
    {
        $recipients = $this->getAllAdmin();

        if ($userId != '')
            $recipients[] = $userId;

        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time'    => now(),
                'type'         => $type,
                'recipient_id' => $recipient,
                'message'      => null,
                'project_id'   => $projectId,
                'article_id'   => null
            ];
        }

        if (count($notifications) > 0) {
            Notification::on()->insert($notifications);
        }
    }

    /**
     * Обновление статуса лида
     *
     * @param $userId
     * @param $lidId
     * @param $type
     * @param $message
     * @return void
     */
    private function updateStatusLid($userId, $lidId, $type, $message)
    {
        $recipients = $this->getAllAdmin();

        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time'    => now(),
                'type'         => $type,
                'message'      => $message,
                'recipient_id' => $recipient,
                'lid_id'       => $lidId,
                'user_id'      => $userId
            ];
        }

        if (count($notifications) > 0) {
            Notification::on()->insert($notifications);
        }
    }

    /**
     * Возвращает массив id всех админов
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAllAdmin()
    {
        return User::on()->select('id')
            ->whereHas('roles', function ($query) {
                $query->where('id', 1);
            })
            ->get()
            ->pluck('id');
    }
}
