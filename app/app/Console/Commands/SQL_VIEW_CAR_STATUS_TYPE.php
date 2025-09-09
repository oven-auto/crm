<?php

namespace App\Console\Commands;

use App\Models\CarStatusType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SQL_VIEW_CAR_STATUS_TYPE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:carstatustype';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать представление, для получения текущего типа статуса (свободный, резер, клиентский, продан)';

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
        /**
         * Спускаемся с конца, то есть еси машина продана, то остальные статусты проставлять смысла нет, 
         * так как продажа последний статус
         * если есть продажа не пустота, то статус SALED
         * иначе если выдача не пустота, то статус ISSUED
         * иначе если резерв не пусто и есть оплата, то статус CLIENT
         * иначе если есть резерв, то статус RESERVED
         * иначе статус FREE
        */

        $query = "CREATE OR REPLACE VIEW car_status_types AS
            SELECT cars.id as car_id,
            IF(sale.id  is not null, '".CarStatusType::VALUES['saled']."', 
                    IF(issue.id is not null, '".CarStatusType::VALUES['issued']."', 
                        IF (reserves.id IS NOT NULL and 
                        SUM(pay.id) IS NOT NULL, '".CarStatusType::VALUES['client']."', IF(
                            reserves.id , '".CarStatusType::VALUES['reserved']."', '".CarStatusType::VALUES['free']."'
                        )
                    )
                )
            ) as status
            FROM `cars`
                LEFT JOIN wsm_reserve_new_cars as reserves
                    on reserves.car_id = cars.id and reserves.deleted_at is NULL
                LEFT JOIN wsm_reserve_new_car_contracts as contracts
                    on contracts.reserve_id = reserves.id
                LEFT JOIN wsm_reserve_issues as issue
                    on issue.reserve_id = reserves.id
                LEFT JOIN wsm_reserve_sales as sale
                    on sale.reserve_id = reserves.id
                LEFT JOIN wsm_reserve_payments as pay
                    on pay.reserve_id = reserves.id
            GROUP  BY cars.id";

        echo ('Создаю/Изменяю представление: car_status_types' . "\r\n");
        echo ('Данное представление хранит текущий тип статуса автомобиля(нового) и содержит следующие столбцы:' . "\r\n");
        echo ('1) car_id - идентификатор из таблицы cars,' . "\r\n");
        echo ('2) status - собственно текущий тип статуса автомобиля(нового), ' . "\r\n");

        DB::statement($query);

        echo ('Закончил работу с car_status_types' . "\r\n" . "\r\n");
    }
}
