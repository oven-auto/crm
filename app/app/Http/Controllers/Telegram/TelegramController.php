<?php

namespace App\Http\Controllers\Telegram;

use App\Classes\Telegram\Scenario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    private $service;



    public function __construct(Scenario $scenario)
    {
        $this->service = $scenario;
    }



    public function get(Request $request)
    {
        if (!$request->has('message'))
            return;

        $this->service->read($request->message);
    }
}
