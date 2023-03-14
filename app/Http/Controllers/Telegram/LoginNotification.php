<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Telegram\Bot\Api;

class LoginNotification extends Controller
{
    public function sendMessage()
    {
        $telegram = new Api('5839877716:AAE61PCamUT7ye1EkMBaUp7KnmIYSZ-BwuQ');

        $telegram->sendMessage([
            'chat_id' => -985138157,
            'text' => 'Выполнен вход',
            'parse_mode' => 'Markdown'
        ]);
    }
}
