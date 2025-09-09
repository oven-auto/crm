<?php

namespace App\Repositories\Worksheet\Modules\Reserve;

use App\Models\Client;
use App\Models\WsmReserveNewCar;
use App\Services\Worksheet\WorksheetClient;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReserveLisingService
{
    public function attach(int $reserve_id, int $client_id)
    {
        DB::transaction(function() use ($reserve_id, $client_id){
            $reserve = WsmReserveNewCar::findOrFail($reserve_id);
        
            $newLisinger = Client::findOrFail($client_id);

            try {
                WorksheetClient::attach($reserve->worksheet, $newLisinger);
            } catch(Throwable $e){

            }finally{
                $reserve->lisinger()->sync($client_id);
            }
        });
    }
    
    
    
    public function detach(int $reserve_id, int $client_id)
    {
        $reserve = WsmReserveNewCar::findOrFail($reserve_id);

        $reserve->lisinger()->detach($client_id);
    }
}

