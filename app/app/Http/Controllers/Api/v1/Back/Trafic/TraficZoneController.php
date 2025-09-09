<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TraficZone;
use App\Http\Resources\Trafic\TraficListCollection;

class TraficZoneController extends Controller
{
    public function index(Request $request)
    {
        $buttons = TraficZone::with('childrens')->select('*')->where('parent',0)->get();

        return new TraficListCollection($buttons);
    }
}
