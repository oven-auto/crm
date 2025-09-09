<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worksheet\Reserve\LisingerCreateRequest;
use App\Repositories\Worksheet\Modules\Reserve\ReserveLisingService;

class ReserveLisingerController extends Controller
{
    private $repo;
    
    public function __construct(ReserveLisingService $repo)
    {
        $this->repo = $repo;
    }
    
    
    /**
     * @OA\Post(
     *      path="/worksheet/modules/reserves/lising",
     *      operationId="appendLisingToreserve",
     *      tags={"Резерв"},
     *      summary="Добавить лизингодателя",
     *      description="Добавить лизингодателя",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/LisingerCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function append(LisingerCreateRequest $request)
    {
        $this->repo->attach($request->get('reserve_id'), $request->get('client_id'));
        
        return response()->json([
            'success' => 1,
            'message' => 'Лизингодатель добавлен.'
        ]);
    }
    
    
    
    /**
     * @OA\Delete(
     *      path="/worksheet/modules/reserves/lising",
     *      operationId="deleteLisingToreserve",
     *      tags={"Резерв"},
     *      summary="Удалить лизингодателя",
     *      description="Удалить лизингодателя",
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/LisingerCreateRequest",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     * )
     */
    public function detach(LisingerCreateRequest $request)
    {
        $this->repo->detach($request->get('reserve_id'), $request->get('client_id'));
        
        return response()->json([
            'success' => 1,
            'message' => 'Лизингодатель удален.'
        ]);
    }
}
