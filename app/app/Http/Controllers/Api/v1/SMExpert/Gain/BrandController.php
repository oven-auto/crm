<?php

namespace App\Http\Controllers\Api\v1\SMExpert\Gain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BrandController extends Controller
{
    public function __invoke()
    {
        // $token = \App\Classes\SMExpert\Token::getInstance()->getToken();

        // $url = 'https://lk.cm.expert/api/v1/cars/appraisals/requests';

        // $mas = [
        //     "contractType" => "tradein",
        //     "customerPhone" => "89042748959",
        //     "customerFirstName" => "ИВАН",
        //     "brandId" => 87,
        //     "modelId" => 3641,
        //     "year" => 2020,
        //     "mileage" => 1000,
        //     "vehicleType" => "pc",
        //     "body" => "sed",
        //     "gear" => "mt",
        //     "drive" => "fwd",
        //     "engine" => "petrol",
        //     "volume" => 1.6,
        //     "power" => 106,
        //     "dealerId" => 10591
        // ];

        // $response = Http::withHeaders([
        //     'Authorization' => $token
        // ])->post($url, $mas);

        // dd($response->body());

        // foreach($response['brands'] as $item)
        //     \App\Models\Brand::create([
        //         'uid' => $item['id'],
        //         'name' => $item['text'],
        //         'slug' => \Str::slug($item['text'])
        //     ]);

        // return response()->json([
        //     'success' => 1
        // ]);
    }
}
