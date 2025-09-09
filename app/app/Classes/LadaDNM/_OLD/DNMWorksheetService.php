<?php

namespace App\Classes\LadaDNM\_OLD;

use App\Classes\LadaDNM\DNM;
use App\Models\DnmWorksheet;
use App\Models\Worksheet;
use Illuminate\Support\Facades\Log;

class DNMWorksheetService
{
    private $dnmService;

    public $worksheet;

    public $dnmWorksheet;

    public $event;

    public function __construct()
    {
        $this->dnmService = DNM::init();

        //$this->worksheet = $worksheet;

        
    }



    /**
     * ЗАПИСАТЬ ОТВЕТ ОТ ДНМ
     */
    private function write(array $data)
    {
        $this->dnmWorksheet = DnmWorksheet::updateOrCreate(
            ['worksheet_id' => $this->worksheet->id],
            ['dnm_id' => $data['id']]
        );
    }



    private function fill()
    {
        return [
            'code' => (string) $this->worksheet->id,
            'client_id' => $this->worksheet->client->dnm->dnm_id,
            'source_id' => "13"
        ];
    }



    private function create()
    {
        $response = $this->dnmService->sendPost('/api/worksheet', $this->fill());

        if ($response->getStatusCode() == 201) {
            $this->write($response->json());

            return 1;
        }

        Log::alert('Не смог создать РЛ (WorksheetId='.$this->worksheet->id.').', $response->json());
    }



    private function update()
    {
        $response = $this->dnmService->sendPut('/api/worksheet/' . $this->dnmWorksheet->dnm_id, $this->fill());

        if ($response->getStatusCode() == 200) {
            $this->write($response->json());

            return 1;
        }

        Log::alert('Не смог изменить РЛ (WorksheetId='.$this->worksheet->id.').', $response->json());
    }



    public function check()
    {
        $response = $this->dnmService->sendGet('/api/worksheet/', ['code' => $this->worksheet->id]);

        if ($response->ok() && count($response->json())) {
            $this->write($response->json()[0]);
            return 1;
        }
        return 0;
    }



    public function save(Worksheet $worksheet)
    {
        $this->worksheet = $worksheet;

        $this->dnmWorksheet = DnmWorksheet::where('worksheet_id', $worksheet->id)->first() ?? new DnmWorksheet();

        if ($this->check())
        {
            Log::alert('Пробую обновить РЛ (WorksheetId='.$worksheet->id.')');
            $this->update();
        }
        else
        {
            Log::alert('Пробую создать РЛ (WorksheetId='.$worksheet->id.')');
            $this->create();
        }
    }
}
