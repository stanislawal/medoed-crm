<?php

namespace App\Helpers;

use Carbon\Carbon;

class DocumentHelper
{
    public static function numberToWords($number)
    {
        return (new \MessageFormatter('ru-RU', '{n, spellout}'))->format(['n' => $number]);
    }

    public static function currentDateFormat($date = null)
    {
        $tmpDate = Carbon::parse(is_null($date) ? now() : $date);

        $monthNameRU = [
            '1'  => 'января',
            '2'  => 'февраля',
            '3'  => 'марта',
            '4'  => 'апреля',
            '5'  => 'мая',
            '6'  => 'июня',
            '7'  => 'июля',
            '8'  => 'августа',
            '9'  => 'сентября',
            '10' => 'октября',
            '11' => 'ноября',
            '12' => 'декабря',
        ];

        $day = Carbon::parse($tmpDate)->format('d');
        $month = Carbon::parse($tmpDate)->format('n');
        $year = Carbon::parse($tmpDate)->format('Y');

        return [
            'day'   => $day,
            'month' => $monthNameRU[$month],
            'year'  => $year
        ];
    }
}
