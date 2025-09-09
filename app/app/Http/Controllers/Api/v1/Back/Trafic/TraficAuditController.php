<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use App\Services\Comment\Comment;
use App\Models\Trafic;
use App\Http\Requests\Trafic\TraficProcessing as TPRequest;
use App\Http\Requests\Trafic\TraficProcessingUpdate;
use App\Models\TraficProcessing;

/**
 * Устарел, не используется
 */
class TraficAuditController extends Controller
{
    public $repository;

    public function __construct(\App\Repositories\Trafic\TraficAuditRepository $service)
    {
        $this->repository = $service;
    }



    public function load(Trafic $trafic, TPRequest $request)
    {
        $audit = $this->repository->saveTraficAudit($trafic, $request->input(), $request->allFiles());

        $trafic = $trafic->fresh();

        Comment::add($audit, 'create');

        return response()->json([
            'data' => $trafic->processing->map(function ($item) {
                return [
                    'scenario' => $item->standart->name,
                    'user' => $item->user->cut_name,
                    'record' => $item->getFile('record'),
                    'audit' => $item->getFile('audit'),
                    'result' => $item->procent,
                    'created_at' => !empty($item->created_at) ? $item->created_at->format('d.m.Y (H:i)') : '',
                    'status' => $item->status_result,
                    'id' => $item->id,
                ];
            }),
            'success' => 1,
            'message' => 'Аудит сохранен',
        ]);
    }



    public function update(TraficProcessing $trafic_processing, TraficProcessingUpdate $request)
    {
        $this->repository->updateTraficAudit($trafic_processing, $request->input(), $request->allFiles());

        $trafic = $trafic_processing->trafic;

        Comment::add($trafic_processing, 'update');

        return response()->json([
            'data' => $trafic->processing->map(function ($item) {
                return [
                    'scenario' => $item->standart->name,
                    'user' => $item->user->cut_name,
                    'record' => $item->getFile('record'),
                    'audit' => $item->getFile('audit'),
                    'result' => $item->procent,
                    'created_at' => !empty($item->created_at) ? $item->created_at->format('d.m.Y (H:i)') : '',
                    'status' => $item->status_result,
                    'id' => $item->id,
                ];
            }),
            'success' => 1,
            'message' => 'Аудит изменен',
        ]);
    }



    public function show(TraficProcessing $trafic_processing)
    {
        Comment::add($trafic_processing, 'show');

        return response()->json([
            'data' => [
                'record' => $trafic_processing->getFile('record'),
                'audit' => $trafic_processing->getFile('audit'),
                'result' => $trafic_processing->result
            ],            
            'success' => 1,
        ]);
    }
}
