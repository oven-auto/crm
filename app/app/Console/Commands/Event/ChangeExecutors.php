<?php

namespace App\Console\Commands\Event;

use App\Models\ClientEvent;
use Illuminate\Console\Command;

class ChangeExecutors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientevent:changeexecutors';

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
        $event = ClientEvent::query()
            ->leftJoin('client_event_statuses', 'client_events.id', 'client_event_statuses.event_id')
            ->leftJoin('client_event_status_executors', 'client_event_status_executors.client_event_status_id', 'client_event_statuses.id')
            ->get();
    }
}
