<?php

namespace App\Http\Controllers\Api\v1\Back\Worksheet;

use App\Http\Controllers\Controller;
use App\Models\Worksheet;
use App\Models\WorksheetFile;
use App\Repositories\Worksheet\WorksheetFileRepository;
use Illuminate\Http\Request;
use App\Services\Comment\Comment;

class WorksheetFileController extends Controller
{
    private $repo;

    public function __construct(WorksheetFileRepository $repo)
    {
        $this->repo = $repo;
    }



    /**
     * Список фаилов в РЛ
     */
    public function index(Worksheet $worksheet)
    {
        $traficFiles = $worksheet->trafic->files->map(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'file' => $item->getFile('filepath'),
                'author' => $item->user->cut_name,
                'created_at' => $item->created_at->format('d.m.Y (H:i)'),
                'type' => 'trafic',
            ];
        });

        $worksheetFiles = $worksheet->files->map(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'file' => $item->getFile(),
                'author' => $item->author->cut_name,
                'created_at' => !empty($item->created_at) ? $item->created_at->format('d.m.Y (H:i)') : '',
                'type' => 'worksheet'
            ];
        });
        

        return response()->json([
            'data' => [
                'trafic' => $traficFiles,
                'worksheet' => $worksheetFiles,
            ],
            'test' => 1,
            'success' => 1,
        ]);
    }



    /**
     * Загрузить фаил
     */
    public function store(Worksheet $worksheet, Request $request)
    {
        $res = collect($this->repo->saveFiles($worksheet, $request->allFiles()));

        Comment::add($res->first(), 'create');

        return response()->json([
            'data' => $res->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'file' => $item->getFile(),
                    'author' => $item->author->cut_name,
                    'created_at' => !empty($item->created_at) ? $item->created_at->format('d.m.Y (H:i)') : '',
                ];
            }),
            'success' => 1,
            'message' => 'Фаилы добавлены',
        ]);
    }



    /**
     * Удалить фаил
     */
    public function delete(WorksheetFile $worksheetfile)
    {
        Comment::add($worksheetfile, 'delete');

        $worksheetfile->delete();

        return response()->json([
            'success' => '1',
            'message' => 'Фаил удален'
        ]);
    }
}
