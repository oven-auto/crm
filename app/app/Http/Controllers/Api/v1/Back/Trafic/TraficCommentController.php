<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use App\Models\Trafic;
use App\Models\TraficComment;
use Illuminate\Http\Request;

class TraficCommentController extends Controller
{
    public function __invoke($trafic_id)
    {
        return response()->json([
            'data' => TraficComment::with('author')->where('trafic_id', $trafic_id)->orderBy('id', 'DESC')->get()->map(function($item){
                return [
                    'author' => $item->author->cut_name,
                    'text' => $item->text,
                    'created_at' => $item->created_at->format('d.m.Y (H:i)'),
                ];
            }),
            'success' => 1,
        ]);
    }
}
