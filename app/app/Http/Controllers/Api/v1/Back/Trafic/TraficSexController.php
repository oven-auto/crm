<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TraficSex;
use App\Http\Resources\Trafic\TraficSexCollection;

class TraficSexController extends Controller
{
    public function index(Request $request)
    {
        $sex = TraficSex::get();

        return new TraficSexCollection($sex);
    }
}
