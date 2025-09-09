<?php

namespace App\Repositories\Trafic;

use App\Services\Download\TraficFileLoad;
use App\Services\FileConverter\Audio;
use App\Models\Trafic;
use App\Models\AuditStandart;
use App\Models\TraficProcessing;

Class TraficAuditRepository
{
    public $loadService;

    public function __construct()
    {
        $this->loadService = new TraficFileLoad();
    }


    
    public function updateTraficAudit(TraficProcessing $traficProcessing, $data = [], $files = [])
    {
        $arr = [];
        if(isset($files['record']))
            $arr['record'] = Audio::wavToMp3($this->loadService->download($traficProcessing->trafic_id, $files['record']));
        if(isset($files['audit']))
            $arr['audit'] = $this->loadService->download($traficProcessing->trafic_id, $files['audit']);
        if(isset($data['result']))
            $arr['result'] = $data['result'];
        $arr['status'] = AuditStandart::find($traficProcessing->audit_standart_id)->target <= $arr['result'] ? true : false;
        $traficProcessing->update($arr);
    }



    public function saveTraficAudit(Trafic $trafic, $data = [], $files)
    {
        $scenario_id = $data['scenario'];

        $coutItemStandart = $trafic->processing->filter(function($item) use ($scenario_id){
            return $item->audit_standart_id == $scenario_id;
        })->count();

        if($coutItemStandart>0)
            throw new \Exception('Странно, но кажется у текущего трафика уже имеется аудит выбранного сценария. Добавить не дам, но можно изменить имеющийся, для этого выберите его в списке аудитов,и нажмите кнопку редактирования');

        $arr['record'] = Audio::wavToMp3($this->loadService->download($trafic->id, $files['record']));
        $arr['audit'] = $this->loadService->download($trafic->id, $files['audit']);
        $arr['result'] = $data['result'];
        $arr['trafic_id'] = $trafic->id;
        $arr['audit_standart_id'] = $data['scenario'];
        $arr['user_id'] = auth()->user()->id;
        $arr['status'] = AuditStandart::find($arr['audit_standart_id'])->target <= $arr['result'] ? true : false;

        $audit = $trafic->processing()->create($arr);

        return $audit;
    }
}
