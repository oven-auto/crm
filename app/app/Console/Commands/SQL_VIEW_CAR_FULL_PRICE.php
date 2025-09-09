<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_CAR_FULL_PRICE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:carfullprice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать представления для хранения детализации цены автомобиля (по прайсу)';

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
        $query = "CREATE OR REPLACE VIEW car_full_prices AS
            SELECT
                IFNULL(cover.price,0) as overprice,
                IFNULL(cop.price,0) as optionprice,
                IFNULL((ccp.price),0) as complectationprice,
                IFNULL((ctp.price),0) as tuningprice,
                (IFNULL(cover.price,0) + IFNULL(cop.price,0) +  IFNULL((ccp.price),0) + IFNULL((ctp.price),0)) as price,
                c.id as car_id,
                ifnull((ccp.id),0) as complectation_price_id,
                ifnull((cgp.price),0) as giftprice
            FROM cars as c
            LEFT JOIN car_tuning_prices as ctp on ctp.car_id = c.id
            LEFT JOIN car_gift_prices as cgp on cgp.car_id = c.id
            LEFT JOIN complectation_current_prices as ccp on ccp.complectation_id = c.complectation_id
            LEFT JOIN car_option_prices as cop on cop.car_id = c.id
            LEFT JOIN car_over_prices as cover on cover.car_id = c.id";

        echo ('Создаю/Изменяю представление: CarFullPrice' . "\r\n");
        echo ('Данное представление хранит детализацию цены автомобиля по прайсу, актуальную на текущую дату и содержит следующие столбцы:' . "\r\n");
        echo ('1) overprice - стоимость дооценки автомобиля,' . "\r\n");
        echo ('2) optionprice - стоимость установленных опций, ' . "\r\n");
        echo ('3) complectationprice - стоимость комплектации' . "\r\n");
        echo ('4) tuningprice - стоимость тюнинга, ' . "\r\n");
        echo ('5) price - полная стоимость автомобиля' . "\r\n");
        echo ('6) car_id - id автомобиля' . "\r\n");

        DB::statement($query);

        echo ('Закончил работу с CarFullPrice' . "\r\n" . "\r\n");
    }
}
