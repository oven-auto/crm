<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Listing;

use App\Http\Controllers\Controller;
use App\Models\CarSaleSign;
use App\Models\RedemptionType;
use Illuminate\Http\Request;

class RedemptionListingController extends Controller
{
    public function type()
    {
        $types = RedemptionType::get();

        return response()->json([
            'data' => $types,
            'success' => 1,
        ]);
    }

    public function sign(int $type = 0)
    {
        $query = CarSaleSign::query();

        if($type)
            $query->where('redemption_type_id', $type);

        $signs = $query->get();

        return response()->json([
            'data' => $signs,
            'success' => 1,
        ]);
    }
}
