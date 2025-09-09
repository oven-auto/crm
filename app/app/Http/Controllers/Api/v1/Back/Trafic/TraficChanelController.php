<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TraficChanel;
use App\Http\Resources\Trafic\TraficListCollection;

class TraficChanelController extends Controller
{
    public function index()
    {
        $chanels = TraficChanel::with('childrens')->where('parent',0)->orderBy('sort')->get();

        return response()->json([
            'data' => $chanels->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'childrens' => $item->childrens,
                    'action_name' => $item->action_name,
                ];
            }),
            'success' => 1,
            'message' => 'Найдено '.$chanels->count().' элементов',
            'count' => $chanels->count()
        ]);
    }
}
