<?php

namespace App\Http\Controllers\Api\v1\SMExpert\Gain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarkController extends Controller
{
    public function __invoke()
    {
        $token = \App\Classes\SMExpert\Token::getInstance()->getToken();

        $urlBodies = 'https://appraisal.api.cm.expert/v1/autocatalog/bodies';
        $responseBodies = Http::withHeaders([
            'Authorization' => $token
        ])->get($urlBodies, []);

        $urlGears = 'https://appraisal.api.cm.expert/v1/autocatalog/gears';
        $responseGears = Http::withHeaders([
            'Authorization' => $token
        ])->get($urlGears, []);

        $urlDrives = 'https://appraisal.api.cm.expert/v1/autocatalog/drives';
        $responseDrives = Http::withHeaders([
            'Authorization' => $token
        ])->get($urlDrives, []);

        $urlEngines = 'https://appraisal.api.cm.expert/v1/autocatalog/engines';
        $responseEngines = Http::withHeaders([
            'Authorization' => $token
        ])->get($urlEngines, []);

        $urlColors = 'https://appraisal.api.cm.expert/v1/autocatalog/colors';
        $responseColors = Http::withHeaders([
            'Authorization' => $token
        ])->get($urlColors, []);

        return response()->json([
            'success' => 12,
            'data' => [
                $responseBodies->json(),
                $responseGears->json(),
                $responseDrives->json(),
                $responseEngines->json(),
                $responseColors->json()
            ],
        ]);
    }
}
