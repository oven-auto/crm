<?php

namespace App\Console\Commands\FormOwner;

use App\Models\FormOwner;
use Illuminate\Console\Command;

class FillOwner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:formowner';

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
        $arr = ['ООО', 'ОАО', 'АО', 'ПАО', 'НАО', 'ЗАО', 'ИП', 'УП', 'АПХ', 'АМР', 'АСП', 'ГБУ', 'ГАУ' ];

        foreach($arr as $item)
            FormOwner::updateOrCreate(['name' => $item],['name' => $item]);
    }
}
