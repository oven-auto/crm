<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TraficZone;

class TraficZoneFill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:traficzonefill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заполнить таблицу зона трафика данными';

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
        $data = [
            ['Неизвестно', 0],
            ['Сыктывкар', 0],
            ['Республика Коми', 0],
            ['Россия', 0],
            ['Воркута', 3],
            ['Вуктыл', 3],
            ['Инта', 3],
            ['Печора', 3],
            ['Сосногорск', 3],
            ['Усинск', 3],
            ['Ухта', 3],
            ['Ижемский р-н', 3],
            ['Княжпогостский р-н', 3],
            ['Койгородский р-н', 3],
            ['Корткеросский р-н', 3],
            ['Прилузский р-н', 3],
            ['Сыктывдинский р-н', 3],
            ['Сысольский р-н', 3],
            ['Троицко-Печорский р-н', 3],
            ['Удорский р-н', 3],
            ['Усть-Вымский р-н', 3],
            ['Усть-Куломский р-н', 3],
            ['Усть-Цилемский р-н', 3],
            ['Кировская обл.', 4],
            ['Архангельская обл.', 4],

        ];
        foreach($data as $item) {
            $obj = new TraficZone();
            $obj->name = $item[0];
            $obj->parent = $item[1];
            $obj->save();
        }
    }
}
