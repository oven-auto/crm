<?php

namespace App\Classes\LadaDNM\Services;

use App\Classes\LadaDNM\DNM;
use App\Classes\LadaDNM\Interfaces\LogInterface;
use App\Classes\LadaDNM\Traits\LogTrait;
use App\Models\DnmWorksheet;
use App\Models\Worksheet;

Class NewDNMWorksheetService implements LogInterface
{
    private $dnmService;
    private $worksheet;
    private $dnmWorksheet;
    private $response;
    private const OOPS = 'Что-то пошло не так, не могу отправить РЛ в ДНМ.';
    private const ENTYTYPE = 'РЛ';

    use LogTrait;

    public function __construct()
    {
        $this->dnmService = DNM::init();        
    }



    public function getEntyId() :string
    {
        return $this->worksheet->id;
    }



    public function getEntyType(): string
    {
        return self::ENTYTYPE;
    }



    /**
     * Подготовить данные для отправки в ДНМ
     */
    private function fill() : array
    {
        return [
            'code' => (string) $this->worksheet->id,
            'client_id' => $this->worksheet->client->dnm->dnm_id,
            'source_id' => "13"
        ];
    }



    /**
     * Записать данные о РЛ полученные от ДНМ
     */
    public function write(array $data) : void
    {
        $this->dnmWorksheet = DnmWorksheet::updateOrCreate(
            ['worksheet_id' => $this->worksheet->id],
            ['dnm_id' => $data['id']]
        );
    }



    /**
     * Проверить существование РЛ
     */
    private function check() : bool
    {
        $response = $this->dnmService->sendGet('/api/worksheet/', ['code' => $this->worksheet->id]);

        if ($response->ok() && count($response->json())) 
        {
            $this->write($response->json()[0]);
            return 1;
        }

        return 0;
    }



    /**
     * Создать РЛ
     */
    public function create() : bool
    {
        $response = $this->dnmService->sendPost('/api/worksheet', $this->fill());

        $this->response = $response;

        if ($response->getStatusCode() == 201) 
        {
            $this->write($response->json());
            return 1;
        }

        return 0;
    }



    /**
     * Изменить РЛ
     */
    public function update() : bool
    {
        $response = $this->dnmService->sendPut('/api/worksheet/' . $this->dnmWorksheet->dnm_id, $this->fill());
        
        $this->response = $response;
        
        if ($response->getStatusCode() == 200) 
        {
            $this->write($response->json());
            return 1;
        }
        
        return 0;
    }



    /**
     * Сохранить РЛ
     */
    public function save(Worksheet $worksheet) : void
    {
        $this->worksheet = $worksheet;

        $this->dnmWorksheet = DnmWorksheet::where('worksheet_id', $worksheet->id)->first() ?? new DnmWorksheet();

        $result = match($this->check()){
            true => $this->update(),
            false => $this->create(),
            default => throw new \Exception(self::OOPS),
        };

        
        $this->log($result);
    }
}