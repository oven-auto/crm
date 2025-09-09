<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Trafic\TraficSexCollection;

class TraficStatusController extends Controller
{
    public function index()
    {
        $data = \App\Models\TraficStatus::where('id', '<>', 6)->get();
        
        $result = $data->map(function($item){
            return (object) [
                'name' => $item->description,
                'id' => $item->id,
            ];
        });
        return new TraficSexCollection($result);
    }
}
