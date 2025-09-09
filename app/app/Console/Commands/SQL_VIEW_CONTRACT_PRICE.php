<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_CONTRACT_PRICE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:contractprice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сделать таблицу представдение для получения актуальной цены кузова контракта';

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
        $query = "CREATE OR REPLACE VIEW contract_complectation_prices AS
            SELECT
	            contract.id as contract_id,
                complectation_prices.id as complectation_price_id

            FROM wsm_reserve_new_car_contracts as contract
            LEFT JOIN (
                SELECT
                    contract.id as reserve_contract_id,
                    max(comprice.id) as complectation_price_id

                FROM wsm_reserve_new_car_contracts as contract

                LEFT JOIN wsm_reserve_new_cars as rnc
                    on contract.reserve_id = rnc.id

                LEFT JOIN cars
                    on cars.id = rnc.car_id

                left JOIN car_full_prices
                    on car_full_prices.car_id = cars.id

                LEFT JOIN complectation_prices as comprice
                    on comprice.complectation_id = cars.complectation_id

                WHERE contract.dkp_offer_at >= comprice.begin_at
                
                GROUP by contract.id

                ORDER BY comprice.id DESC
            ) as cc on cc.reserve_contract_id = contract.id

            LEFT JOIN complectation_prices on complectation_prices.id = cc.complectation_price_id
            LEFT JOIN wsm_reserve_new_cars as reservecar on reservecar.id = contract.reserve_id
            LEFT JOIN car_full_prices as fullp on fullp.car_id = reservecar.car_id


        ";

        DB::statement($query);
    }
}
