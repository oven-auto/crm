<?php

namespace App\Console\Commands\WSMService;

use App\Models\ServicePayment;
use Illuminate\Console\Command;

class FillPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $arr = [
            'Наличными',
            'Р/с дилера',
            'Р/с поставщика',
            'В цене АМ',
        ];

        $current = ServicePayment::get();

        foreach($arr as $name)
        {
            if(!$current->contains('name', $name))
                ServicePayment::create(['name' => $name]);
        }
    }
}
