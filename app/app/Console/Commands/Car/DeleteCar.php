<?php

namespace App\Console\Commands\Car;

use App\Models\Car;
use App\Models\WsmReserveNewCar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DeleteCar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-car';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $str = 'Точно удалить все новые машины из таблицы cars?';
       
        if(env('APP_URL') != 'http://192.168.1.98:8280')
        {
            $this->info('Это не тестовая песочница. Удалять нельзя.');
            die();
        }

        if($this->confirm($str))
        {
            Schema::disableForeignKeyConstraints();
            Car::truncate();
            WsmReserveNewCar::truncate();
            Schema::enableForeignKeyConstraints();
        }
    }
}
