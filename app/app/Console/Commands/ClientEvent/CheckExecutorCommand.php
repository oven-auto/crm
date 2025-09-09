<?php

namespace App\Console\Commands\ClientEvent;

use App\Models\ClientEvent;
use App\Models\ClientEventStatusExecutor;
use Illuminate\Console\Command;

class CheckExecutorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientevent:executor';

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
        $events = ClientEvent::with(['statuses.executors'])->get();

        $events->each(function($item){
            $item->statuses->each(function($status) use($item){
                $status->executors()->attach($item->author_id);
            });
        });

        $executors = ClientEventStatusExecutor::get();
        
        $data = [];
        $executors->each(function($item) use (&$data){
            if(!isset($data[$item->client_event_status_id.'_'.$item->user_id]))
                $data[$item->client_event_status_id.'_'.$item->user_id] = [
                    'client_event_status_id'    => $item->client_event_status_id,
                    'user_id'                   => $item->user_id
                ];
        });
       
        ClientEventStatusExecutor::truncate();

        foreach($data as $item) {
            ClientEventStatusExecutor::create($item);
        }
    }
}
