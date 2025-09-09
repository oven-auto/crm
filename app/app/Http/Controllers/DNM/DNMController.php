<?php

namespace App\Http\Controllers\DNM;

use App\Classes\LadaDNM\Services\DNMEvent;
use App\Classes\LadaDNM\Services\NewDNMClientService;
use App\Http\Controllers\Controller;
use App\Models\WsmReserveNewCar;
use Illuminate\Http\Request;

class DNMController extends Controller
{
    public function index(WsmReserveNewCar $reserve, Request $request)
    {
        if(!$request->has('action'))
            return 0;

        match($request->action) {
            'client' => (new NewDNMClientService())->save($reserve->worksheet->client, $reserve->worksheet),
            'visit' => (new DNMEvent())->handler($reserve, 'visit'),
            'contract' => (new DNMEvent())->handler($reserve, 'contract'),
            'issue' => (new DNMEvent())->handler($reserve, 'issue'),
            default => '',
        };
    }
}
