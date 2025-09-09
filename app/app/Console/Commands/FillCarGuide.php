<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FillCarGuide extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'car:guide';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заполнить все вспомогательные справочники для модели CAR';

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
        $logisticState = [
            ['system_name' => 'off_date',       'state' => 0,   'name' => 'Списание'],
            ['system_name' => 'ransom_date',    'state' => 0,   'name' => 'Оплата поставщику'],
            ['system_name' => 'presale_date',   'state' => 0,   'name' => 'Предпродажка'],



            ['system_name' => 'order_date',     'state' => 1,   'name' => 'Заказ'],
            ['system_name' => 'plan_date',      'state' => 2,   'name' => 'Сборка планируемая'],
            ['system_name' => 'build_date',     'state' => 3,   'name' => 'Сборка фактическая'],
            ['system_name' => 'ready_date',     'state' => 4,   'name' => 'Готовность к отгрузке'],
            ['system_name' => 'request_date',   'state' => 5,   'name' => 'Заявка на перевозку'],
            ['system_name' => 'shipment_date',  'state' => 6,   'name' => 'Отгрузка'],
            ['system_name' => 'stock_date',     'state' => 7,   'name' => 'Приемка на склад'],

            ['system_name' => 'invoice_date',   'state' => 8,   'name' => 'Приходная накладная'],

        ];

        foreach ($logisticState as $item) {
            \App\Models\LogisticState::updateOrCreate(
                ['system_name' => $item['system_name']],
                $item
            );
        }

        ///////////////////////////////////////////////

        // $terms = [
        //     ['name' => 'Предоплата',    'sort' => 1],
        //     ['name' => 'Факторинг',     'sort' => 2],
        //     ['name' => 'Консигнация',   'sort' => 3],
        //     ['name' => 'Гарантия',      'sort' => 4],
        //     ['name' => 'Акредитив',     'sort' => 5],
        // ];

        // foreach ($terms as $item) {
        //     $item['text_color'] = '#000';
        //     \App\Models\DeliveryTerm::updateOrCreate(
        //         ['name' => $item['name']],
        //         $item
        //     );
        // }

        /////////////////////////////////////////////

        // $orderTypes = [
        //     ['name' => 'Квота'],
        //     ['name' => 'Перевод'],
        //     ['name' => 'Обмен'],
        //     ['name' => 'Поиск'],
        //     ['name' => 'Возврат'],
        // ];

        // foreach ($orderTypes as $item)
        //     \App\Models\OrderType::updateOrCreate(
        //         ['name' => $item['name']],
        //         $item
        //     );

        ////////////////////////////////////////////////////

        $tradeMarks = [
            ['name' => 'Товарный автомобиль'],
            ['name' => 'Подменный автомобиль'],
            ['name' => 'Демо автомобиль'],
            ['name' => 'Возврат'],
        ];

        foreach ($tradeMarks as $item)
            \App\Models\TradeMarker::updateOrCreate(
                ['name' => $item['name']],
                $item
            );

        //////////////////////////////////////////////////

        // $detailingCosts = [
        //     ['name' => 'Доставка'],
        // ];

        // foreach ($detailingCosts as $item)
        //     \App\Models\DetailingCost::updateOrCreate(
        //         ['name' => $item['name']],
        //         $item
        //     );

        $carStates = [
            ['order_date', 'В заявке', 'in_order'],
            ['plan_date', 'Сборка', 'in_plan'],
            ['build_date', 'Собран', 'in_build'],
            ['ready_date', 'Готов к отгрузке', 'in_ready'],
            ['request_date', 'Заявлен в отгрузку', 'in_request'],
            ['shipment_date', 'Отгружен', 'in_shipment'],
            ['stock_date', 'Разгружен', 'in_stock'],
            ['invoice_date', 'На складе', 'in_invoice'],
            ['off_date', 'Списан', 'in_off'],
            ['presale_date', 'Прошёл предпродажку', 'in_presale'],
            ['ransom_date', 'Выкуплен у поставщика', 'in_ransom'],
        ];

        foreach ($carStates as $item) {
            \App\Models\CarState::updateOrCreate(
                ['logistic_system_name' => $item[0]],
                [
                    'description' => $item[1],
                    'status' => $item[2]
                ]
            );
        }
    }
}
