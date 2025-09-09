<?php

namespace App\Console\Commands\System;

use App\Models\SalePriority;
use Illuminate\Console\Command;

class SalePriorityFillCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sale:priority';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = [
            [
                'id'    => 1,
                'name'  => 'Предзаказ',
            ],
            [
                'id'    => 2,
                'name'  => 'Свежее поступление',
            ],
            [
                'id'    => 3,
                'name'  => 'Платный период',
            ],
            [
                'id'    => 4,
                'name'  => 'Просроченная дебиторка',
            ],
            [
                'id'    => 5,
                'name'  => 'Проблемный склад',
            ],
            [
                'id'    => 6,
                'name'  => 'Токсичный склад',
            ],
        ];

        foreach($data as $item)
            SalePriority::updateOrCreate(
                ['id'       => $item['id']],
                ['name'     => $item['name']],
            );
    }
}
