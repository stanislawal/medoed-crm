<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{

    // возвращает список дней недели
    public static function getWeekdayList()
    {
        return [
            'Monday' => 'Понедельник ',
            'Tuesday' => 'Вторник',
            'Wednesday' => 'Среда',
            'Thursday' => 'Четверг',
            'Friday' => 'Пятница',
            'Saturday' => 'Суббота',
            'Sunday' => 'Воскресенье',
        ];
    }

    // Возвращает текущий день недели
    public static function getWeekday()
    {
        return Carbon::now()->format('l');
    }
}
