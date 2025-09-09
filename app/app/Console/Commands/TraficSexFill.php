<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TraficSex;

class TraficSexFill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:traficsexfill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заполнить таблицу тип клиента';

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
            'Неизвестно', 'Мужчина', 'Женщина', 'Компания',
        ];
        foreach($data as $item) {
            $sex = new TraficSex();
            $sex->name = $item;
            $sex->save();
        }
    }
}
