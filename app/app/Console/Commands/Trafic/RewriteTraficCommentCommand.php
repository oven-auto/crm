<?php

namespace App\Console\Commands\Trafic;

use App\Models\ClientType;
use App\Models\TraficClient;
use App\Models\TraficControl;
use App\Models\TraficMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class RewriteTraficCommentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trafic:comment';

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

        ProgressBar::setFormatDefinition('custom', ' %current%/%max% [%bar%] %message%');

        Auth::attempt([
            'email' => 'oit@oven-auto.ru',
            'password' => 'Jdty2019'
        ]);
        
        $trafics = DB::table('trafics')->get();

        $defaultClientTypeId = ClientType::first()->id;

        $progressBar = $this->output->createProgressBar($trafics->count());
        
        $progressBar->setFormat('custom');

        echo "Переиндексирую трафик".PHP_EOL;

        $progressBar->start();
        
        $start_time = microtime(true);

        $currentTime = 0;

        foreach($trafics as $key => $item)
        {
            $end_time = microtime(true);

            $time = ($end_time - $start_time);

            $progressBar->setMessage('ОЗУ '.round(memory_get_usage()/1048576).'мб. [Время выполнения = '.$time.']');
            
            $progressBar->advance();

            if($item->comment)
                TraficMessage::updateOrCreate(
                    ['trafic_id' => $item->id,],
                    [
                        'message' => $item->comment,
                        'author_id' => $item->author_id
                    ]
                );

            if(
                $item->firstname || $item->phone || 
                $item->inn || $item->email || 
                $item->company_name || $item->client_type_id || 
                $item->trafic_sex_id
            )
                TraficClient::updateOrCreate(
                    ['trafic_id' => $item->id,],
                    [
                        'client_type_id' => $item->client_type_id ?? $defaultClientTypeId,
                        'trafic_sex_id' => $item->trafic_sex_id,
                        'firstname' => $item->firstname,
                        'lastname' => $item->lastname,
                        'fathername' => $item->fathername,
                        'phone' => $item->phone,
                        'email' => $item->email,
                        'inn' => $item->inn,
                        'company_name' => $item->company_name,
                    ]
                );


            if($item->begin_at || $item->end_at)
                TraficControl::updateOrCreate(
                    [
                        'trafic_id' => $item->id,
                    ],
                    [
                        'begin_at'  => $item->begin_at,
                        'end_at'    => $item->end_at,
                        'interval'  => $item->interval
                    ]
                );
        }
        $progressBar->finish();
    }
}
