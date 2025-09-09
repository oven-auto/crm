<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trafic\LinkResource as TraficLinkResource;
use App\Http\Resources\Worksheet\Link\LinkCollection;
use App\Http\Resources\Worksheet\Link\LinkResource;
use App\Models\Interfaces\CommentInterface;
use App\Models\Worksheet;
use App\Models\WorksheetLink;
use App\Services\GetShortCutFromURL\GetShortCutFromURL;
use Illuminate\Http\Request;
use App\Services\Comment\Comment;

class WorksheetLinkController extends Controller
{
    /**
     * СПИСОК ВСЕХ ССЫЛОК РЛ
     */
    public function index(Worksheet $worksheet, Request $request)
    {
        $links = $worksheet->links;

        $trafics = $worksheet->trafic->links;

        return response()->json([
            'data' => [
                'worksheet'     => LinkResource::collection($links),
                'trafic'        => TraficLinkResource::collection($trafics),
            ],
            'success' => 1,
        ]);
    }



    /**
     * СОЗДАТЬ ССЫЛКУ
     */
    public function store(Worksheet $worksheet, Request $request)
    {
        $link = $worksheet->links()->create([
            'author_id' => auth()->user()->id,
            'icon' => GetShortCutFromURL::get($request->url),
            'url' => $request->url,
        ]);

        if($link instanceof CommentInterface)
            Comment::add($link, 'create');

        return (new LinkResource($link))->additional([
            'success' => 1, 'message' => 'Ссылка добавлена'
        ]);
    }



    /**
     * УДАЛИТЬ ССЫЛКУ
     */
    public function delete(WorksheetLink $worksheetLink)
    {
        Comment::add($worksheetLink, 'delete');

        $worksheetLink->delete();

        return response()->json([
            'message' => 'Ссылка удалена',
            'success' => 1,
        ]);
    }
}
