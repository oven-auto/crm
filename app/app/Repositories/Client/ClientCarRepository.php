<?php

namespace App\Repositories\Client;

use App\Models\ClientCar;
use App\Models\Client;

Class ClientCarRepository
{
    public function find(array $data)
    {
        $query = ClientCar::query()
            ->leftJoin('client_phones', 'client_phones.client_id', 'client_cars.client_id');
        
        if(isset($data['phone']))
            $query->where('client_phones.phone', $data['phone']);

        $cars = $query->get();

        return $cars;
    }



    /**
     * Создать машину клиента
     * @param Client $client App\Models\Client
     * @param array $data Данные о машине
     * @return ClientCar
     */
    public function store(Client $client, $data = []) : ClientCar
    {
        if(isset($data['action']))
            unset($data['action']);

        if(isset($data['odometer'])) {
            $odometer = '';
            $array = (str_split($data['odometer']));
            foreach($array as $item) {
                if(is_numeric($item))
                    $odometer.=$item;
            }
            $data['odometer'] = $odometer;
        }

        $data['actual'] = 1;
        $data['author_id'] = auth()->user()->id;
        $data['editor_id'] = auth()->user()->id;

        $mas = array_map(function($item){
            if($item)
                return $item;
        }, $data);

        return $client->cars()->create($mas);
    }


    
    /**
     * Изменить машину клиента
     * @param ClientCar $car App\Models\ClientCar
     * @param array $data Данные о машине
     * @return void
     */
    public function update(ClientCar $car, $data = []) : void
    {
        if(isset($data['action']))
            unset($data['action']);

        if(isset($data['odometer'])) {
            $odometer = '';
            $array = (str_split($data['odometer']));
            foreach($array as $item) {
                if(is_numeric($item))
                    $odometer.=$item;
            }
            $data['odometer'] = $odometer;
        }

        $data['editor_id'] = auth()->user()->id;

        $mas = array_map(function($item){
            if($item)
                return $item;
        }, $data);

        $car->fill($mas)->save();
    }

    

    /**
     * Спрятать машину клиента
     * @param ClientCar $car App\Models\ClientCar
     * @return void
     */
    public function hide(ClientCar $car) : void
    {
        $car->actual = 0;
        $car->save();
    }



    /**
     * Количество машин клиента
     * @param Client $client App\Models\Client
     * @return int
     */
    public function amountClientCar($client) : int
    {
        $amountClientCar = $client->cars->where('actual',1)->count();
        return $amountClientCar;
    }
}

