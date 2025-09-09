<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TraficChanel;

class TraficChanelFill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:traficchanelfill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Залить стандартные данные в каналы трафика';

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
            ['Посещение', 0],
            ['Входящий звонок', 0],
            ['Интернет-запрос', 0],
            ['Маркетинг', 0],
            ['Социальные сети', 0],
            ['Поисковые системы', 0],
            ['Маркетплейсы', 0],
            ['Сайт компании', 3],
            ['Лэндинг', 3],
            ['Агрегатор Calltouch', 3],
            ['Агрегатор Callkeeper', 3],
            ['SMS-рассылка', 4],
            ['MAIL-рассылка', 4],
            ['Почтовая рассылка', 4],
            ['Холодный звонок', 4],
            ['Контакт BTL', 4],
            ['Контакт ATL', 4],
            ['LID ВКонтакте', 5],
            ['LID Instagram', 5],
            ['LID Facebook', 5],
            ['LID Одноклассники', 5],
            ['Яндекс карты', 6],
            ['2GIS', 6],
            ['LID Avito.ru', 7],
            ['LID Auto.ru', 7],
        ];
        foreach($data as $item) {
            $obj = new TraficChanel();
            $obj->name = $item[0];
            $obj->parent = $item[1];
            $obj->save();
        }
    }
}
