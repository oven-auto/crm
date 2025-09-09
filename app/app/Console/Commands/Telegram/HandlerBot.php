<?php

namespace App\Console\Commands\Telegram;

use Illuminate\Console\Command;

class HandlerBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tgbot:handler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $bot; 

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\App\Classes\Telegram\Scenario $scenario)
    {
        parent::__construct();

        $this->bot = $scenario;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->bot->handler();
    }
}
