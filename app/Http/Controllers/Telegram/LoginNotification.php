<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Telegram\Bot\Api;

class LoginNotification extends Controller
{
    public function sendMessage()
    {
        $telegram = new Api('5949325459:AAEwGmoVP395lF35el2VDWrNc08VanQVOfk');

        $telegram->sendMessage([
           'chat_id' => 364650472,
           'text' => 'Выполнен вход',
            'parse_mode' => 'Markdown'
        ]);
    }
}
