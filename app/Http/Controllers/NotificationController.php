<?php

namespace App\Http\Controllers;


use App\Constants\NotificationTypeConstants;
use App\Helpers\UserHelper;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notification = Notification::on()
            ->where('recipient_id', UserHelper::getUserId())
            ->orderBy('is_viewed', 'desc')
            ->orderBy('date_time', 'desc')
            ->get()
            ->toArray();

        return view('', [
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
            ->where('recipient_id', UserHelper::getUserId())
            ->where('is_viewed', false)
            ->orderBy('date_time', 'desc')
            ->get()
            ->toArray();

        return redirect()->json([
            'result' => true,
            'html' => view('', ['notifications' => $notifications])->render(),
            'count' => count($notifications)
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
        Notification::on()->where('id', $id)->update(['is_viewed' => 1]);

        if ($request->ajax()) {
            return response()->json(['result' => true]);
        }
        return redirect()->back();
    }

    /**
     * Создать уведомление
     *
     * @param $type
     * @param $userId
     * @param $id
     * @param $data
     * @return void
     */
    public function createNotification($type, $userId, $id, $data = null)
    {
        switch ($type) {
            // назначение прокта на менеджера
            case NotificationTypeConstants::ASSIGNED_PROJECT :
                $this->assignedProject($userId, $id);
                break;

            // редактирование статьи
            case NotificationTypeConstants::CHANGE_ARTICLE :
                $this->changeArticle($userId, $id);
                break;

            // редактирование статьи
            case NotificationTypeConstants::CHANGE_PRICE_PROJECT :
                $this->changePriceProject($userId, $id);
                break;

        }
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
        $recipients[] = $userId;

        $notifications = [];
        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time' => now(),
                'type' => NotificationTypeConstants::ASSIGNED_PROJECT,
                'recipient_id' => $recipient,
                'project_id' => $projectId,
                'article_id' => null
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
    private function changeArticle($userId, $articleId)
    {
        $recipients = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 1);
        })->get()->pluck('id'); // получить всех админов

        $notifications = [];
        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time' => now(),
                'type' => NotificationTypeConstants::CHANGE_ARTICLE,
                'recipient_id' => $recipient,
                'message' => null,
                'project_id' => null,
                'article_id' => $articleId
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
        $recipients = User::on()->whereHas('roles', function ($query) {
            $query->where('id', 1);
        })->get()->pluck('id'); // получить всех админов

        $notifications = [];
        foreach ($recipients as $recipient) {
            $notifications[] = [
                'date_time' => now(),
                'type' => NotificationTypeConstants::CHANGE_PRICE_PROJECT,
                'recipient_id' => $recipient,
                'message' => null,
                'project_id' => $projectId,
                'article_id' => null
            ];
        }

        if (count($notifications) > 0) {
            Notification::on()->insert($notifications);

        }
    }
}
