<?php

namespace App\Http\Controllers\Api\v1\Back\Service;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    public function index(Request $request)
    {
        $rules = ['service_id' => 'required'];

        $valid = $request->validate($rules);

        $providers = Service::find($valid['service_id'])->with('providers');

        return $providers;
    }
}
