<?php

namespace App\Console\Commands\Trafic;

use App\Repositories\Worksheet\WorksheetRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateWorksheetFromTrafic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'worksheet:fake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->service = new WorksheetRepository();

        Auth::attempt([
            'email' => 'oit@oven-auto.ru',
            'password' => 'Jdty2019'
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $traficIds = DB::table('trafics')->select('id')->pluck('id');

        $progressBar = $this->output->createProgressBar($traficIds->count());
        
        $progressBar->start();

        foreach($traficIds as $item)
        {
            $progressBar->advance();

            $this->service->createFromTrafic($item);
        }

        $progressBar->finish();

        echo PHP_EOL."ЗАКОНЧИЛ.".PHP_EOL;
    }
}
