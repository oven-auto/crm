<?php

namespace App\Console\Commands;

use App\Classes\LadaDNM\DNM;
use App\Models\ReasonRefusal;
use Illuminate\Console\Command;

class DNM_REASON_REFUSAL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dnm:reason';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить список причин отказа и записать их к себе в табличку';

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

        $reasons = $dnm->getResults();

        foreach ($reasons as $item) {
            ReasonRefusal::create([
                'id'                => $item['id'],
                'name'              => $item['name'],
                'for_lead'          => $item['for_lead'],
                'for_reject'        => $item['for_reject'],
                'reject_contract'   => $item['reject_contract'],
            ]);
        };
    }
}
