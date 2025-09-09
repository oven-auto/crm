<?php

namespace App\Classes\LadaDNM\Services;

use App\Classes\LadaDNM\DNM;
use App\Classes\LadaDNM\Interfaces\LogInterface;
use App\Classes\LadaDNM\Traits\LogTrait;
use App\Models\ClientCar;
use App\Models\DnmClientCar;

Class DNMVehicleService implements LogInterface
{
    private $dnmService;
    private $car;
    private $dnmCar;
    private $response;
    private const OOPS = 'Что-то пошло не так, не могу отправить авто клиента в ДНМ.';
    private const ENTYTYPE = 'АВТО КЛИЕНТА';

    use LogTrait;

    public function __construct()
    {
        $this->dnmService = DNM::init();
    }


    public function getEntyId(): string
    {
        return $this->car->id;
    }



    public function getEntyType(): string
    {
        return self::ENTYTYPE;
    }



    public function fill() : array
    {
        return [
            'brand_id'      => $this->car->brand->dnm->dnm_brand_id,
            'model_id'       => $this->car->mark->dnm->dnm_mark_id,
            'code'          => (string)$this->car->id,
            'vin'           => $this->car->vin ?? null,
            'color'         => $this->car->color->name ?? null,
            'mileage'       => $this->car->odometer,
            'reg_number'    => $this->car->register_plate,
        ];
    }



    public function write(array $data) : void
    {
        DnmClientCar::updateOrCreate(
            ['client_car_id' => $this->car->id],
            ['dnm_client_car_id' => $data['id']],
        );
    }



    public function check() : bool
    {
        $response = $this->dnmService->sendGet('/api/vehicle/', ['code' => $this->car->id]);
        
        if ($response->ok() && count($response->json())) 
        {
            $this->write($response->json()[0]);
            return 1;
        }

        return 0;
    }



    public function create() : bool
    {
        $response = $this->dnmService->sendPost('/api/vehicle', $this->fill());
        
        $this->response = $response;
        
        if ($response->getStatusCode() == 201) 
        {
            $this->write($response->json());
            return 1;
        }
        
        return 0;
    }



    public function update() : bool
    {
        $response = $this->dnmService->sendPut('/api/vehicle/'.$this->dnmCar->dnm_client_car_id, $this->fill());

        $this->response = $response;
        
        if ($response->getStatusCode() == 200) 
        {
            $this->write($response->json());
            return 1;
        }
        
        return 0;
    }



    public function save(ClientCar $car) : void
    {   
        if(!$car->mark->dnm->dnm_mark_id)
            return;
   
        $this->car = $car;
        
        $this->dnmCar = DnmClientCar::where('client_car_id', $this->car->id)->first() ?? new DnmClientCar();

        $result = match($this->check()){
            true => $this->update(),
            false => $this->create(),
            default => throw new \Exception(self::OOPS),
        };

        $this->log($result);
    }
}


