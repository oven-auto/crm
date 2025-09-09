<?php

namespace App\Http\Controllers\Api\v1\Back\Audit\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\Default\SuccessResource;
use App\Models\Audit\AuditRecord;
use App\Services\Download\TraficFileLoad;
use App\Services\FileConverter\Audio;
use Illuminate\Http\Request;

class AuditRecordController extends Controller
{
    /**
     * @OA\Get(
     *      path="/audits/record",
     *      operationId="getAuditRecordList",
     *      tags={"Аудит стандартов"},
     *      summary="Получить ИД записи выбранного мастера",
     *      description="Получить ИД записи выбранного мастера (master_id = 1)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $vaildated = $request->validate([
            'master_id' => 'required'
        ]);

        $record = AuditRecord::where('master_id', $vaildated['master_id'])->first();

        return response()->json([
            'data' => [
                'id' => $record->id ?? '',
            ],
            'success' => 1,
        ]);
    }



    /**
     * @OA\Get(
     *      path="/audits/{recordId}",
     *      operationId="getAuditRecord",
     *      tags={"Аудит стандартов"},
     *      summary="Получить фаил записи",
     *      description="Получить фаил записи",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function show(int $id)
    {
        $record = AuditRecord::findOrFail($id);
        
        $fileName = 'audit_record_'.$record->id.'.wav';

        return response($record->file)
            ->header('Content-Type', 'blob')
            ->header('Content-Transfer-Encoding', 'Binary')
            ->header('Content-disposition', 'attachment; filename="'.$fileName.'"');
    }



    /**
     * @OA\Post(
     *      path="/audits/record",
     *      operationId="PostAuditRecordList",
     *      tags={"Аудит стандартов"},
     *      summary="Создать фаил записи",
     *      description="Создать фаил записи (master_id = id, file = *.wav)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function store(Request $request)
    {
        $service  = new TraficFileLoad();

        $vaildated = $request->validate([
            'master_id' => 'required',
            'file' => 'required|file|mimes:wav',
        ]);

        $tmp = Audio::wavToMp3($service->download($request->master_id, $request->file));
       
        $vaildated['file'] = file_get_contents(storage_path('app/public'.$tmp));

        unlink(storage_path('app/public'.$tmp));

        $record = AuditRecord::create($vaildated);

        return response()->json([
            'data' => [
                'id' => $record->id,
            ],
            'success' => 1,
        ]);
    }



    /**
     * @OA\Patch(
     *      path="/audits/record/{recordId}",
     *      operationId="PatchAuditRecordList",
     *      tags={"Аудит стандартов"},
     *      summary="Изменить фаил записи",
     *      description="Изменить фаил записи (master_id = id, file = *.wav)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function update(int $id, Request $request)
    {
        $service  = new TraficFileLoad();

        $vaildated = $request->validate([
            'master_id' => 'required',
            'file' => 'required|file|mimes:wav',
        ]);

        $tmp = Audio::wavToMp3($service->download($request->master_id, $request->file));
       
        $vaildated['file'] = file_get_contents(storage_path('app/public'.$tmp));

        $record = AuditRecord::findOrFail($id);
        
        $record->fill($vaildated);

        if($record->isDirty())
            $record->save();
        
        unlink(storage_path('app/public'.$tmp));

        return response()->json([
            'data' => [
                'id' => $record->id,
            ],
            'success' => 1,
        ]);
    }



    /**
     * @OA\Delete(
     *      path="/audits/record/{recordId}",
     *      operationId="DeleteAuditRecordList",
     *      tags={"Аудит стандартов"},
     *      summary="Удалить фаил записи",
     *      description="Удалить фаил записи",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     * )
     */
    public function destroy(int $id)
    {
        $record = AuditRecord::findOrFail($id);

        $record->delete();

        return new SuccessResource([]);
    }
}
