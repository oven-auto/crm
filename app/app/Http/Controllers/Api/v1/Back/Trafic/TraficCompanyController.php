<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class TraficCompanyController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Company::get(),
            'success' => Company::get() ? 1 : 0,
        ]);
    }
}
