<?php

namespace App\Console\Commands\Telegram;

use App\Classes\Telegram\Notice\TelegramNotice;
use App\Jobs\TelegramJob;
use App\Models\TelegramConnection;
use App\Models\User;
use Illuminate\Console\Command;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tgbot:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $options = [
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        $user = TelegramConnection::select('user_id')
            ->leftJoin('users', 'users.telegram_connection_id', 'telegram_connections.id')
            ->where('users.id', 47)
            ->first();

        (TelegramJob::dispatch($user->user_id, 'test', $options));
    }
}
