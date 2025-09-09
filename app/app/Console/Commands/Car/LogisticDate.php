<?php

namespace App\Console\Commands\Car;

use App\Models\CarState;
use App\Models\LogisticState;
use Illuminate\Console\Command;

class LogisticDate extends Command
{
    private const ARR_STATE = [
        ['Заявка в производство',       'application_date',     0],
        ['Сборка фактическая',          'build_date',	        3,],
        ['Приходная накладная',         'invoice_date',	        8,],
        ['Выдан',                       'issue_date',	        9,],
        ['Списание',                    'off_date',	            0,],
        ['Заказ',                       'order_date',	        1,],
        ['Сборка планируемая',          'plan_date',	        2,],
        ['Предпродажка',                'presale_date',	        0,],
        ['Оплата поставщику',           'ransom_date',	        0,],
        ['Готовность к отгрузке',       'ready_date',	        4,],
        ['Заявка на перевозку',         'request_date',	        5,],
        ['Продан',                      'sale_date',	        10,],
        ['Отгрузка',                    'shipment_date',	    6,],
        ['Приемка на склад',            'stock_date',	        7,],
    ];



    private const ARR_STATUSES = [
        ['order_date',           'В заказе',                 'in_order',        2],
        ['plan_date',            'Сборка',                   'in_plan',         3],
        ['build_date',           'Собран',                   'in_build',        4],
        ['ready_date',           'Готов к отгрузке',         'in_ready',        5],
        ['request_date',         'Заявлен в отгрузку',       'in_request',      6],
        ['shipment_date',        'Отгружен',                 'in_shipment',     7],
        ['stock_date',           'В приёмке',                'in_stock',        8],
        ['invoice_date',         'На складе',                'in_invoice',      9],
        ['off_date',             'Списан',                   'in_off',          0],
        ['presale_date',         'Прошёл предпродажку',      'in_presale',      0],
        ['ransom_date',          'Выкуплен у поставщика',    'in_ransom',       0],
        ['issue_date',           'Выдан',                    'in_issue',        0],
        ['sale_date',            'Продан',                   'in_sale',         0],
        ['application_date',     'В заявке',                 'in_application',  1],
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'car:logistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $logistics = LogisticState::get();
        
        foreach(self::ARR_STATE as $item)
            if(!$logistics->contains('system_name', $item[1]))
                LogisticState::create([
                    'name' => $item[0],
                    'system_name' => $item[1],
                    'state' => $item[2],
                ]);

        $statuses = CarState::get();

        foreach(self::ARR_STATUSES as $status)
            //if(!$statuses->contains('logistic_system_name', $status[0]))
                CarState::updateOrCreate([
                    'logistic_system_name' => $status[0],
                ],
                [
                    'description' => $status[1],
                    'status' => $status[2],
                    'sort' => $status[3]
                ]);
    }
}




