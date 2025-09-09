<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TraficOwnerController extends Controller
{
    public function __invoke()
    {
        $owner = \App\Models\Client::getOwner();

        return response()->json([
            'data' => [
                'company_name' => $owner->company_name,
                'trafic_zone_id' => $owner->trafic_zone_id,
                'inn' => $owner->inn->number,
                'trafic_chanel_id' => \App\Models\TraficChanel::where('type',1)->first()->id
            ],
            'success' => 1,
        ]);
    }
}
