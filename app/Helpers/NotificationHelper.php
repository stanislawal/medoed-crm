<?php

namespace App\Helpers;

class NotificationHelper
{
    public static function getLastNotViewedNotify()
    {
        $notifications = \App\Models\Notification::on()->where('recipient_id', \App\Helpers\UserHelper::getUserId())
            ->where('is_viewed', false)
            ->with(['projects:id,project_name,manager_id', 'articles:id,article', 'projects.projectUser:id,full_name'])
            ->orderBy('date_time', 'desc')
            ->get();

        return $notifications;
    }
}
