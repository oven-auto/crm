<?php

namespace App\Console\Commands\Telegram\LongPolling;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DeleteWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tgbot:delhook';

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
        $webUrl = 'https://telegram.oven-auto.ru/get';

        $url = env('TELEGRAM_URL').env('TELEGRAM_KEY').'/deleteWebhook';

        $res = Http::get($url);
    }
}
