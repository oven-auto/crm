<?php

namespace App\Classes\LadaDNM\Services;

use App\Classes\LadaDNM\DNM;
use App\Models\DnmWorksheetEvent;
use App\Models\WsmReserveNewCar;
use Illuminate\Support\Facades\Log;

class DNMEvent
{
    private $dnm;

    public function __construct()
    {
        $this->dnm = DNM::init();
    }



    public function handler(WsmReserveNewCar $reserve, string $type)
    {
        Log::channel('dnm')->alert('Пробую создать событие в DNM "'.strtoupper($type).'" для резерва '.$reserve->id.'.');

        match ($type) {
            'visit'         => $this->visit($reserve),
            'reject'        => $this->reject($reserve),
            'call'          => $this->call($reserve),
            'testdrive'     => $this->testdrive($reserve),
            'offer'         => $this->offer($reserve),
            'internet'      => $this->internet($reserve),
            'contract'      => $this->contract($reserve),
            'issue'         => $this->issue($reserve),
            default => ''
        };
    }



    public function save(WsmReserveNewCar $reserve, array $data)
    {
        DnmWorksheetEvent::create([
            'reserve_id' => $reserve->id,
            'dnm_event_id' => $data['id'],
            'dnm_worksheet_id' => $data['worksheet_id'],
            'dnm_appeal_id' => $data['car_id'],
            'status' => $data['status'],
            'code' => $data['code'],
            'event_type' => $data['event_type'],
        ]); 
    }



    /**
     * ВИЗИТ
     */
    public function visit(WsmReserveNewCar $reserve)
    {
        $data = [
            'event_type' => 'visit',
            'worksheet_id' => $reserve->worksheet->dnm->dnm_id,
            'code' => (string)$reserve->id.date('-dmYHis'),
            'occurred' => now()->format('d.m.Y H:i:s'),
            'manager_id' => 1433,
            'car_id' => $reserve->dnm->dnm_appeal_id,
        ];

        $response = $this->dnm->sendPost('/api/event', $data);
       
        if ($response->getStatusCode() == 201) {
            $this->save($reserve, $response->json());
            return;
        }

        Log::channel('dnm')->emergency('Не получилось создать событие ВИЗИТ в ЛадаДНМ.', $response->json());
    }



    /**
     * ОТМЕНА
     */
    public function reject(WsmReserveNewCar $reserve)
    {
        $data = [
            'event_type' => 'reject',
            'worksheet_id' => $reserve->worksheet->dnm->dnm_id,
            'code' => (string)$reserve->id.date('-dmYHis'),
            'occurred' => now()->format('d.m.Y H:i:s'),
            'manager_id' => 1433,
            'car_id' => $reserve->dnm->dnm_appeal_id,
            'result_id' => 12,
        ];

        $response = $this->dnm->sendPost('/api/event', $data);
       
        if ($response->getStatusCode() == 201) {
            $this->save($reserve, $response->json());
            return;
        }

        Log::channel('dnm')->emergency('Не получилось создать событие ОТКАЗ в ЛадаДНМ.', $response->json());
    }



    /**
     * ЗВОНОК
     */
    public function call(WsmReserveNewCar $reserve)
    {
        $data = [
            'event_type' => 'call',
            'worksheet_id' => $reserve->worksheet->dnm->dnm_id,
            'code' => (string)$reserve->id.date('-dmYHis'),
            'occurred' => now()->format('d.m.Y H:i:s'),
            'manager_id' => 1433,
            'car_id' => $reserve->dnm->dnm_appeal_id,
        ];

        $response = $this->dnm->sendPost('/api/event', $data);

        if ($response->getStatusCode() == 201) {
            $this->save($reserve, $response->json());
            return;
        }

        Log::channel('dnm')->emergency('Не получилось создать событие ЗВОНОК в ЛадаДНМ.', $response->json());
    }



    /**
     * ИНТЕРНЕТ ЗАЯВКА
     */
    public function internet(WsmReserveNewCar $reserve)
    {
        $reserve->load('dnm');

        $data = [
            'event_type' => 'internet',
            'worksheet_id' => $reserve->worksheet->dnm->dnm_id,
            'code' => (string)$reserve->id.date('-dmYHis'),
            'occurred' => now()->format('d.m.Y H:i:s'),
            'manager_id' => 1433,
            'car_id' => $reserve->dnm->dnm_appeal_id,
        ];

        $response = $this->dnm->sendPost('/api/event', $data);

        if ($response->getStatusCode() == 201) {
            $this->save($reserve, $response->json());
            return;
        }

        Log::channel('dnm')->emergency('Не получилось создать событие ИНТЕРНЕТ-ОБРАЩЕНИЕ в ЛадаДНМ.', $response->json());
    }



    /**
     * ТЕСТДРАЙВ
     */
    public function testdrive(WsmReserveNewCar $reserve)
    {
        $data = [
            'event_type' => 'test-drive',
            'worksheet_id' => $reserve->worksheet->dnm->dnm_id,
            'code' => (string)$reserve->id.date('-dmYHis'),
            'occurred' => now()->format('d.m.Y H:i:s'),
            'manager_id' => 1433,
            'car_id' => $reserve->dnm->dnm_appeal_id,
        ];

        $response = $this->dnm->sendPost('/api/event', $data);

        if ($response->getStatusCode() == 201) {
            $this->save($reserve, $response->json());
            return;
        }

        Log::channel('dnm')->emergency('Не получилось создать событие TEST-DRIVE в ЛадаДНМ.', $response->json());
    }



    /**
     * КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ
     */
    public function offer(WsmReserveNewCar $reserve)
    {
        $data = [
            'event_type' => 'offer',
            'worksheet_id' => $reserve->worksheet->dnm->dnm_id,
            'code' => (string)$reserve->id.date('-dmYHis'),
            'occurred' => now()->format('d.m.Y H:i:s'),
            'manager_id' => 1433,
            'car_id' => $reserve->dnm->dnm_appeal_id,
        ];

        $response = $this->dnm->sendPost('/api/event', $data);

        if ($response->getStatusCode() == 201) {
            $this->save($reserve, $response->json());
            return;
        }

        Log::channel('dnm')->emergency('Не получилось создать событие OFFER в ЛадаДНМ.', $response->json());
    }



    /**
     * КОНТРАКТ
     */
    public function contract(WsmReserveNewCar $reserve)
    {
        $data = [
            'event_type' => 'contract',
            'worksheet_id' => $reserve->worksheet->dnm->dnm_id,
            'code' => (string)$reserve->id.date('-dmYHis'),
            'occurred' => now()->format('d.m.Y H:i:s'),
            'manager_id' => 1433,
            'car_id' => $reserve->dnm->dnm_appeal_id,
        ];

        $response = $this->dnm->sendPost('/api/event', $data);

        if ($response->getStatusCode() == 201) {
            $this->save($reserve, $response->json());
            return;
        }

        Log::channel('dnm')->emergency('Не получилось создать событие CONTRACT в ЛадаДНМ.', $response->json());
    }



    public function issue(WsmReserveNewCar $reserve)
    {
        $data = [
            'event_type' => 'issue',
            'worksheet_id' => $reserve->worksheet->dnm->dnm_id,
            'code' => (string)$reserve->id.date('-dmYHis'),
            'occurred' => now()->format('d.m.Y H:i:s'),
            'manager_id' => 1433,
            'car_id' => $reserve->dnm->dnm_appeal_id,
            'vin' => $reserve->car->vin,
        ];

        $response = $this->dnm->sendPost('/api/event', $data);

        if ($response->getStatusCode() == 201) {
            $this->save($reserve, $response->json());
            return;
        }

        Log::channel('dnm')->emergency('Не получилось создать событие ISSUE в ЛадаДНМ.', $response->json());
    }



    public function update(int $eventId, string $newStatus)
    {
        $data = [
            'status' => $newStatus,
            'occurred' => now()->format('d.m.Y H:i:s'),
        ];

        $response = $this->dnm->sendPut('/api/event/'.$eventId, $data);

        if ($response->getStatusCode() == 201 || $response->getStatusCode() == 200) {
            $dnmEvent = DnmWorksheetEvent::where('dnm_event_id', $eventId)->first();

            $dnmEvent->fill([
                'status' => $data['status'],
            ])->save();

            return;
        }
        
    }
}
