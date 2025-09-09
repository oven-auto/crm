<?php

namespace App\Console\Commands;

use App\Classes\LadaDNM\DNM;
use Illuminate\Console\Command;

class DNM_MARK_ALIASES extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dnm:aliases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить все синонимы моделей';

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
        $dnm = DNM::init();

        $aliases = $dnm->getModelAliases();
        
        foreach ($aliases as $item) {
            dump($item);
            \App\Models\MarkAlias::updateOrCreate(
                ['dnm_id' => $item['id']],
                [
                    'name' => $item['name'],
                    'dnm_brand_id' => $item['brand_id'],
                    'dnm_model_id' => $item['model_id']
                ]
            );
        }
    }
}
