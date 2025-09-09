<?php

namespace App\Classes\Telegram;

use App\Jobs\TelegramJob;
use App\Models\TelegramConnection;

Class SystemMessage
{
    public static function send(string $message = 'Системное сообщение')
    {
        $options = [
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        $user = TelegramConnection::select('user_id')
            ->leftJoin('users', 'users.telegram_connection_id', 'telegram_connections.id')
            ->where('users.id', 47)
            ->first();

        (TelegramJob::dispatch($user->user_id, $message, $options));
    }
}