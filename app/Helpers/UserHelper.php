<?php

namespace App\Helpers;


use App\Models\User;

class UserHelper
{

    //Возврвщает id аторизованного пользователя
    public static function getUserId()
    {
        return auth()->user()->id ?? null;
    }

    public static function getUser()
    {
        return auth()->user();
    }

    public static function isAdmin()
    {
        return auth()->user()->hasRole('Администратор');
    }

    public static function isManager()
    {
        return auth()->user()->hasRole('Менеджер');
    }

    public static function getRoleName($userId = null)
    {
        if (is_null($userId)) {
            $roles = auth()->user()->getRoleNames();
        } else {
            $roles = User::on()->find($userId)->getRoleNames();
        }

        return $roles[0] ?? 'Неопределено';
    }

    public static function getMonth()
    {
        $month = [
            [
                'month' => 1,
                'name' => 'Январь'
            ],
            [
                'month' => 2,
                'name' => 'Февраль'
            ],
            [
                'month' => 3,
                'name' => 'Март'
            ],
            [
                'month' => 4,
                'name' => 'Апрель'
            ],
            [
                'month' => 5,
                'name' => 'Май'
            ],
            [
                'month' => 6,
                'name' => 'Июнь'
            ],
            [
                'month' => 7,
                'name' => 'Июль'
            ],
            [
                'month' => 8,
                'name' => 'Август'
            ],
            [
                'month' => 9,
                'name' => 'Сентябрь'
            ],
            [
                'month' => 10,
                'name' => 'Октябрь'
            ],
            [
                'month' => 11,
                'name' => 'Ноябрь'
            ],
            [
                'month' => 12,
                'name' => 'Декабрь'
            ],

        ];
        return $month;
    }


}




