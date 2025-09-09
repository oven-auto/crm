<?php

namespace App\Console\Commands\Telegram;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TelegramBot extends Command
{
    protected $signature = 'tgbot:run';

    protected $description = 'Запустить телеграм бота';

    protected $bot;

    private static $test = false;

    public function __construct(\App\Classes\Telegram\Scenario $scenario)
    {
        parent::__construct();

        $this->bot = $scenario;
    }


    
    public function handle()
    {
        //$test = 1;


        while(true)
        {
    
           sleep(1);
           try {
                $this->bot->handler();
           }
           catch(\Exception $e)
           {
              Log::channel('telegram')->error('Ошибка в цикле: '.$e->getMessage());
           }
       }
    }
}
