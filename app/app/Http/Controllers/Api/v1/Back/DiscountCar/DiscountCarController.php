<?php

namespace App\Http\Controllers\Api\v1\Back\DiscountCar;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCar\DiscountCarRequest;
use App\Http\Resources\Discount\DiscountResource;
use App\Models\DiscountType;
use Illuminate\Http\Request;
use App\Repositories\Discount\DiscountRepository;

class DiscountCarController extends Controller
{
    private $repo;

    public function __construct(DiscountRepository $repo)
    {
        $this->repo = $repo;
    }



    /**
     * 
     */
    public function index(Request $request)
    {
        $discounts = $this->repo->all($request->all());

        return response()->json([
            'data' => DiscountResource::collection($discounts),
            'success' => 1,
        ]);
    }



    public function show(DiscountType $discounttype)
    {
        return response()->json([
            'data' => new DiscountResource($discounttype),
            'success' => 1,
        ]);
    }



    public function store(DiscountType $discounttype, DiscountCarRequest $request)
    {
        $this->repo->save($discounttype, $request->validated());

        return response()->json([
            'data' => new DiscountResource($discounttype),
            'success' => 1,
            'message' => 'Тип скидки создан'
        ]);
    }



    public function update(DiscountType $discounttype, DiscountCarRequest $request)
    {
        $this->repo->save($discounttype, $request->validated());

        return response()->json([
            'data' => new DiscountResource($discounttype),
            'success' => 1,
            'message' => 'Тип скидки изменен'
        ]);
    }



    public function destroy(DiscountType $discounttype)
    {
        $this->repo->delete($discounttype);

        return response()->json([
            'data' => 'delete',
            'success' => 1,
            'message' => 'Тип скидки удален'
        ]);
    }



    public function restore(DiscountType $discounttype)
    {
        $this->repo->restore($discounttype);

        return response()->json([
            'data' => 'Restore',
            'success' => 1,
            'message' => 'Тип скидки актуален'
        ]);
    }
}
