<?php

namespace App\Classes\LadaDNM\_OLD;

use App\Classes\LadaDNM\DNM;
use App\Models\Client;
use App\Models\DnmClient;
use App\Models\WsmReserveNewCar;
use Illuminate\Support\Facades\Log;

class DNMClientService
{
    private $dnmService;

    private $obj;

    private $dnmClient;

    private $reserve;

    public function __construct()
    {
        $this->dnmService = DNM::init();
    }



    /**
     * ЗАПИСАТЬ ОТВЕТ ОТ ДНМ
     * ХРАНЮ id клиента нашей системы
     * и id клиента в системе ДНМ
     */
    private function write(array $data)
    {
        $this->dnmClient->fill([
            'client_id' => $this->obj->id,
            'dnm_id' => $data['id']
        ])->save();
    }



    /**
     * Сформировать данные для отправки на ДНМ
     */
    private function fill()
    {
        $confirm = 0;
        $personal = 0;

        if($this->reserve->contract)
        {
            $confirm = 1;
            $personal = 1;
        }

        if ($this->obj->isPerson())
            return [
                'name'          => $this->obj->firstname ?? 'Неизвестно',
                'last_name'     => $this->obj->lastname,
                'middle_name'   => $this->obj->fathername,
                'phone_hash'    => $this->obj->phones->count() ? sha1($this->obj->phones[0]) : '',
                'sex'           => $this->obj->sex->dnm_id ?? '',
                'code'          => (string)$this->obj->id,
                'type'          => $this->obj->getDnmTypeClient() ?? '',
                'email'         => $this->obj->email ? $this->obj->email->first()->email : '',
                'address'       => $this->obj->passport ? $this->obj->passport->address : '',
                'birthday'      => $this->obj->passport ? $this->obj->passport->birthday : '',                
                'client_confirm_communication'  => $confirm,
                'may_process_personal_data'     => $personal,

                'phones'        => [
                    [
                        'type'      => 1,
                        'number'    => $this->obj->phones->first()->phone,
                    ]
                ]
            ];
        elseif (!$this->obj->isPerson())
        {
            $client = $this->reserve->worksheet->subclients->where('client_type_id', 1)->first();

            $client->load('phones');
            
            return [
                'code'                  => (string)$this->obj->id,
                'company_name'          => (string)$this->obj->company_name,
                'type'                  => $this->obj->getDnmTypeClient() ?? '',
                'company_address'       => $this->obj->zone->name ?? '',
                "name"                  => $client->firstname,
                'last_name'             => $client->lastname,
                'middle_name'           => $client->fathername,
                "company_legal_form"    => "ООО",
                "company_address"       => "Сыктывкар",
                "email"                 => "",
                "company_email"         => "",
                "phones" => [
                    // [
                    // "type" => 1,
                    // "number" => $client->phones->first()->phone,
                    // ],
                    [
                    "type" => 2,
                    "number" => $client ? $client->phones->first()->phone : '',
                    ],
                ],
                'client_confirm_communication'  => $confirm,
                'may_process_personal_data'     => $personal,
            ];
        }
    }



    /**
     * ЗАПРОС НА СОЗДАНИЕ КЛИЕНТА ДНМ
     */
    private function create()
    {
        $data = $this->fill();

        $response = $this->dnmService->sendPost('/api/client', $data);

        if ($response->getStatusCode() == 201) {
            $this->write($response->json());
            return 1;
        }

        Log::alert('Не получилось создать клиента (ClientId='.$this->obj->id.') на портале ДНМ', $response->json());
    }



    /**
     * ЗАПРОС НА ИЗМЕНЕНИЕ КЛИЕНТА В ДНМ
     */
    private function update()
    {
        $data = $this->fill();
        
        $response = $this->dnmService->sendPut('/api/client/' . $this->dnmClient->dnm_id, $data);
        
        if ($response->getStatusCode() == 200) {
            $this->write($response->json());
            return 1;
        }

        Log::alert('Не получилось изменить клиента (ClientId='.$this->obj->id.') на портале ДНМ', $response->json());
    }



    public function check()
    {
        $response = $this->dnmService->sendGet('/api/client/', ['code' => $this->obj->id]);

        if ($response->ok() && count($response->json())) {
            $this->write($response->json()[0]);
            return 1;
        }
        return 0;
    }



    /**
     * ОБРАБОТЧИК
     */
    public function save(WsmReserveNewCar $reserve)
    {
        $this->reserve = $reserve;

        $this->obj = $reserve->worksheet->client;

        $this->dnmClient = \App\Models\DnmClient::where('client_id', $this->obj->id)->first() ?? new DnmClient();

        if ($this->check())
        {
            Log::alert('Пробую обновить клиента (ClientId='.$this->obj->id.').');
            $this->update();
        }
        else
        {
            Log::alert('Пробую создать клиента (ClientId='.$this->obj->id.').');
            $this->create();
        }
    }



    public function getDnmId()
    {
        return $this->dnmClient->dnm_id;
    }
}
