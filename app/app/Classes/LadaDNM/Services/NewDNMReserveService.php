<?php

namespace App\Classes\LadaDNM\Services;

use App\Classes\LadaDNM\DNM;
use App\Classes\LadaDNM\Interfaces\LogInterface;
use App\Classes\LadaDNM\Traits\LogTrait;
use App\Models\DnmWorksheetAppeal;
use App\Models\WsmReserveNewCar;

Class NewDNMReserveService implements LogInterface
{
    private $dnmService;
    private $reserve;
    private $dnmReserve;
    private $alias;
    private $response;
    private const OOPS = 'Что-то пошло не так, не могу отправить РЕЗЕРВ в ДНМ.';
    private const ENTYTYPE = 'РЕЗЕРВ';

    use LogTrait;

    public function __construct()
    {
        $this->dnmService = DNM::init();
    }



    public function getEntyId() :string
    {
        return $this->reserve->id;
    }



    public function getEntyType(): string
    {
        return self::ENTYTYPE;
    }



    private function getAlias()
    {
        $alias = $this->reserve->car->complectation->alias->alias;

        return $alias;
    }



    private function write(array $data)
    {
        $this->dnmReserve = DnmWorksheetAppeal::updateOrCreate(
            ['reserve_id' => $this->reserve->id,],
            [
                'dnm_appeal_id' => $data['id'],
                'dnm_worksheet_id' => $this->reserve->worksheet->dnm->id,
            ]
        );
    }



    public function fill()
    {
        $code = $this->reserve->created_at()->format('ymd').$this->reserve->worksheet->id.$this->reserve->id;

        return [
            'code'              => $code,
            'brand_id'          => $this->alias->dnm_brand_id ?? '',
            'model_alias_id'    => $this->alias->dnm_id ?? '',
            'vin'               => $this->reserve->car->vin,
        ];
    }



    public function check() : bool
    {
        $response = $this->dnmService->sendGet('/api/need/', ['code' => $this->reserve->id]);
        
        if ($response->ok() && count($response->json())) 
        {
            $this->write($response->json()[0]);
            return 1;
        }

        return 0;
    }



    public function create()
    {
        $response = $this->dnmService->sendPost('/api/need', $this->fill());
        
        $this->response = $response;
        
        if ($response->getStatusCode() == 201) 
        {
            $this->write($response->json());
            return 1;
        }
        
        return 0;
    }



    public function update()
    {
        $response = $this->dnmService->sendPut('/api/need/'.$this->dnmReserve->dnm_appeal_id, $this->fill());

        $this->response = $response;

        if ($response->getStatusCode() == 200) 
        {
            $this->write($response->json());
            return 1;
        }
        
        return 0;
    }



    public function save(WsmReserveNewCar $reserve) : void
    {
        $this->reserve = $reserve;

        $this->alias = $this->getAlias();

        $this->dnmReserve = DnmWorksheetAppeal::where('reserve_id', $this->reserve->id)->first() ?? new DnmWorksheetAppeal();

        $result = match($this->check()){
            true => $this->update(),
            false => $this->create(),
            default => throw new \Exception(self::OOPS),
        };

        $this->log($result);
    }
}