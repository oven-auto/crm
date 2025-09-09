<?php

namespace App\Http\Controllers\Api\v1\Listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ZoneController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => \App\Models\TraficZone::select(['id','name'])
                ->where('trafic_zones.parent','<>','0')
                ->orWhere(DB::raw('(select count(zz.id) from trafic_zones as zz where zz.parent = trafic_zones.id)'),'<','1')
                ->orderBy('trafic_zones.parent')
                ->orderBy('trafic_zones.name')
                ->get(),
            'success' => 1,
        ]);
    }
}
