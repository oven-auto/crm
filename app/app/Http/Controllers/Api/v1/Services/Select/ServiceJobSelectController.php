<?php

namespace App\Http\Controllers\Api\v1\Services\Select;

use App\Http\Controllers\Controller;
use App\Models\ServiceJob;

class ServiceJobSelectController extends Controller
{
    public function index() 
    {
    	$data = ServiceJob::get();
    	return response()->json([
    		'data' => $data,
    		'success' => $data->count() ? 1 : 0
    	]);
    }
}
