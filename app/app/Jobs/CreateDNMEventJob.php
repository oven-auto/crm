<?php

namespace App\Jobs;

use App\Classes\LadaDNM\Services\DNMEvent;
use App\Classes\LadaDNM\Services\NewDNMClientService;
use App\Classes\LadaDNM\Services\NewDNMReserveService;
use App\Classes\LadaDNM\Services\NewDNMWorksheetService;
use App\Models\WsmReserveNewCar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDNMEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reserve; 

    public $action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WsmReserveNewCar $reserve, string $action)
    {
        $this->reserve = $reserve;

        $this->action = $action;
    }

    /**
     * Джоба для отправки события в ДНМ
     *
     * @return void
     */
    public function handle()
    {
        (new NewDNMClientService())->save($this->reserve->worksheet->client, $this->reserve->worksheet);

        (new NewDNMWorksheetService())->save($this->reserve->worksheet);

        (new NewDNMReserveService())->save($this->reserve);

        (new DNMEvent())->handler($this->reserve, $this->action);
    }
}
