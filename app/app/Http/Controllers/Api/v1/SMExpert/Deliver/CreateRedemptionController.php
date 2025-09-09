<?php

namespace App\Http\Controllers\Api\v1\SMExpert\Deliver;

use App\Classes\SMExpert\CMExpertService;
use App\Http\Controllers\Controller;
use App\Models\WSMRedemptionCar;
use App\Repositories\Worksheet\Modules\Redemption\RedemptionRepository;
use Illuminate\Http\Request;

class CreateRedemptionController extends Controller
{
    public function __invoke(WSMRedemptionCar $redemption, CMExpertService $service)
    {
        $service->send($redemption);

        $redemption->load('apprailsal');

        return response()->json([
            'data' => [
                'url' => $redemption->apprailsal->url(),
                'created_at' => $redemption->apprailsal->created_at->format('d.m.Y (H:i)'),
                'author' => $redemption->apprailsal->author->cut_name,
            ],
            'success' => 1,
        ]);
    }
}
