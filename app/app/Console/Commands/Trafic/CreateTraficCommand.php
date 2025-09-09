<?php

namespace App\Console\Commands\Trafic;

use App\Models\Trafic;
use App\Models\TraficChanel;
use App\Models\TraficZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateTraficCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trafic:fake';

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
        $countClient = 10000;

        //DB::table('trafics')->delete();

        $progressBar = $this->output->createProgressBar($countClient);
        
        $progressBar->start();
        
        for($i = 0; $i <= $countClient; $i++)
        {         
            $progressBar->advance();

            $trafic = $this->saveTrafic();

            $this->saveClient($trafic, $i);

            $this->saveControl($trafic, $i);

            $this->savemessage($trafic, $i);
        }

        $progressBar->finish();

        echo PHP_EOL."ЗАКОНЧИЛ.".PHP_EOL;
    }



    public function saveTrafic() : Trafic
    {
        return Trafic::create([
            'author_id' => Auth::id(),
            'trafic_zone_id' => TraficZone::inRandomOrder()->first()->id,
            'trafic_chanel_id' => TraficChanel::inRandomOrder()->first()->id,
            'company_id' => 1,
            'company_structure_id' => 1,
            'trafic_appeal_id' => 7,
            'trafic_status_id' => 2,
            'manager_id' => Auth::id()
        ]);
    }



    public function saveClient(Trafic $trafic, int $i) : void
    {
        $trafic->client()->create([
            'client_type_id' => 1,
            'trafic_sex_id' => 2,
            'firstname' => 'Клиент '.($i+1),
            'lastname' => 'Клиент '.($i+1),
            'fathername' => 'Клиент '.($i+1),
            'phone' => 19999999999-$i,
            'trafic_id' => $trafic->id,
        ]);
    }



    public function saveControl(Trafic $trafic, int $i) : void
    {
        $interval = rand(10,30);

        $trafic->control()->create([
            'trafic_id' => $trafic->id,
            'begin_at' => now(),
            'end_at' => now()->addMinutes($interval),
        ]);
    }



    public function savemessage(Trafic $trafic, int $i)
    {
        $trafic->message()->create([
            'author_id' => Auth::id(),
            'trafic_id' => $trafic->id,
            'message' => 'Клиент '.($i+1),
        ]);
    }
}
