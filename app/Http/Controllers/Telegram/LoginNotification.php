<?php

namespace App\Http\Controllers\Telegram;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use Telegram\Bot\Api;

class LoginNotification extends Controller
{
    public function sendMessage()
    {
        $telegram = new Api('5839877716:AAE61PCamUT7ye1EkMBaUp7KnmIYSZ-BwuQ');
        $ip = $_SERVER['REMOTE_ADDR'];
        $login = UserHelper::getUser();
        $telegram->sendMessage([
            'chat_id' => -985138157,
            'text' => 'Вошел '.$login['full_name'].' '.' IP - '.$ip,
            'parse_mode' => 'Markdown'
        ]);
    }
}
