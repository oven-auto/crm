<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SQL_VIEW_CONTRACT_OPTION_PRICE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:contractoptionprice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать представление для хранения цены (по ДКП контракта) опций автомобиля из резерва';

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
        $query = "CREATE OR REPLACE VIEW contract_option_prices AS

            SELECT
                contract.id as contract_id,
                option_prices.id as option_price_id

            FROM wsm_reserve_new_car_contracts as contract

            LEFT JOIN wsm_reserve_new_cars as reserve on reserve.id = contract.reserve_id

            LEFT JOIN car_options on car_options.car_id = reserve.car_id

            LEFT JOIN option_prices on option_prices.option_id = car_options.option_id

            WHERE option_prices.id in (
                SELECT

                    max(option_prices.id)

                FROM wsm_reserve_new_car_contracts as contract

                LEFT JOIN wsm_reserve_new_cars as reserve on contract.reserve_id = reserve.id

                LEFT JOIN cars on cars.id = reserve.car_id

                LEFT JOIN car_options on cars.id = car_options.car_id

                LEFT JOIN option_prices on option_prices.option_id = car_options.option_id

                WHERE contract.dkp_offer_at >= option_prices.begin_at

                GROUP BY option_prices.option_id
            )
        ";

        \DB::statement($query);
    }
}
