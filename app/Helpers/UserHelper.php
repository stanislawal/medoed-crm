<?php

namespace App\Helpers;


use App\Models\CrossArticleRedactor;
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

    public static function isAuthor()
    {
        return auth()->user()->hasRole('Автор');
    }

    public static function isRedactor()
    {
        return (boolean)CrossArticleRedactor::on()->where('user_id', self::getUserId())->count();
    }

    public static function getRoleName($userId = null)
    {
        if (is_null($userId)) {
            $roles = auth()->user()->getRoleNames();
        } else {
            $roles = User::on()->find($userId)->getRoleNames();
        }

        return $roles[0] ?? null;
    }


}




