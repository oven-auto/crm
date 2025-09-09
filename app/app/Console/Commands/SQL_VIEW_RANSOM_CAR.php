<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_RANSOM_CAR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:ransomcar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать представление хранящее идентификаторы выкупленных машин';

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
        $query = "CREATE OR REPLACE VIEW ransom_cars as 
            SELECT cdl.car_id from car_date_logistics as cdl
            WHERE cdl.logistic_system_name = 'ransom_date'";

        DB::statement($query);
    }
}
