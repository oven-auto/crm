<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class SQL_VIEW_CAR_OPTION_PRICE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:optionprice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать/Изменить представление для хранения суммы стоимости установленных на автомобиль опций';

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
        $query = "CREATE OR REPLACE VIEW car_option_prices AS
            SELECT co.car_id, sum(ocp.price) as price
            FROM car_options as co
            LEFT JOIN option_current_prices as ocp on ocp.option_id = co.option_id
            GROUP BY co.car_id";

        echo ('Создаю/Изменяю представление: CarOptionPrice' . "\r\n");
        echo ('Данное представление хранит текущую актуальную сумму стоимости опций и содержит следующие столбцы:' . "\r\n");
        echo ('1) car_id - идентификатор из таблицы cars,' . "\r\n");
        echo ('2) price - собственно текущая сумма стоимости опций, ' . "\r\n");

        \DB::statement($query);

        echo ('Закончил работу с CarOptionPrice' . "\r\n" . "\r\n");
    }
}
