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

class CreateDNMReserveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reserve;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WsmReserveNewCar $reserve)
    {
        $this->reserve = $reserve;
    }

    /**
     * Джоба для создания резерва в ДНМ
     *
     * @return void
     */
    public function handle()
    {
        if($this->reserve->worksheet->isLada() && $this->reserve->worksheet->isSaleDepartment() && $this->reserve->worksheet->isSaleNewCar())
        {
            (new NewDNMClientService())->save($this->reserve->worksheet->client, $this->reserve->worksheet);

            (new NewDNMWorksheetService())->save($this->reserve->worksheet);

            (new NewDNMReserveService())->save($this->reserve);
            
            $action = match($this->reserve->worksheet->trafic->chanel->id) {
                1         => 'visit',
                2         => 'call',
                default     => 'internet',
            };

            (new DNMEvent())->handler($this->reserve, $action);
        }
    }
}
