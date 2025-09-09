<?php

namespace App\Console\Commands\BackUp;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BackUpCommand extends Command
{
    protected $signature = 'app:backup';
    
    protected $description = 'Command description';


    
    public function handle()
    {
        while(true)
        {
            Artisan::call("backup:run --only-db");

            sleep(60*60*24);
        }
    }
}
