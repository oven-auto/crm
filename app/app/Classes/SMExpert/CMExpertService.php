<?php

namespace App\Classes\SMExpert;

use App\Models\WSMRedemptionCar;
use Illuminate\Support\Facades\Http;

Class CMExpertService
{
    private const FIELDS = [
        "contractType" => 'Тип контракта',
        "customerPhone" => 'Телефон клиента',
        "customerFirstName" => 'Имя клиента',
        "brandId" => 'Марка авто',
        "modelId" => 'Модель авто',
        "year" => 'Год выпуска авто',
        "mileage" => 'Пробег авто',
        "vehicleType" => 'Тип ТС',
        "body" => 'Кузов авто',
        "gear" => 'Трансмиссия авто',
        "drive" => 'Привод авто',
        "engine" => 'Тип мотора',
        "volume" => 'Объем двигателя',
        "power" => 'Мощность',
        "dealerId" => 'Код дилера',
        "vin"   => 'VIN',
    ];

    private const URL = 'https://lk.cm.expert/api/v1/cars/appraisals/requests';



    public function send(WSMRedemptionCar $redemption)
    {
        if($redemption->apprailsal)
            throw new \Exception('Оценка этого автомобиля уже была создана на CME.');

        if(!$redemption->client_car->vin)
            throw new \Exception('Укажите VIN автомобиля клиента.');

        $token = \App\Classes\SMExpert\Token::getInstance()->getToken();

        $mas = [
            "contractType" => $redemption->car_sale_sign->acronym,
            "customerPhone" => '9000000001',//$redemption->client->phones->first()->phone,
            "customerFirstName" => 'Клиент',//$redemption->client->firstname,
            "brandId" => $redemption->client_car->brand->uid,
            "modelId" => $redemption->client_car->mark->uid,
            "year" => $redemption->client_car->year,
            "mileage" => $redemption->client_car->odometer,
            "vehicleType" => $redemption->client_car->vehicle->acronym,
            "body" => $redemption->client_car->bodywork->acronym,
            "gear" => $redemption->client_car->transmission->acronym,
            "drive" => $redemption->client_car->drive->acronym,
            "engine" => $redemption->client_car->type->acronym,
            "volume" => (float)str_replace(',', '.', $redemption->client_car->motor_size),
            "power" => $redemption->client_car->motor_power,
            "dealerId" => (int)env('CME_DEALER_ID'),
            "customerExpectedBuyoutPrice" => $redemption->expectation,
            "color" => $redemption->client_car->color->acronym,
        ];

        foreach($mas as $key => $item)
            if(!$item)
                throw new \Exception("Поле ".self::FIELDS[$key]." не заполнено");

        if($redemption->client_car->vin)
            $mas["vin"] = strtoupper($redemption->client_car->vin);

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post(self::URL, $mas);

        if($response->status() === 201)
        {
            $redemption->apprailsal()->create([
                'author_id' => auth()->user()->id,
                'link_id' => $response['id'],
            ]);

            return $response['id'];
        }

        throw new \Exception('Ошибка API: '.$response->body());
    }
}
