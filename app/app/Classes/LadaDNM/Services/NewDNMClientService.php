<?php

namespace App\Classes\LadaDNM\Services;

use App\Classes\LadaDNM\DNM;
use App\Classes\LadaDNM\Interfaces\LogInterface;
use App\Classes\LadaDNM\Traits\LogTrait;
use App\Models\Client;
use App\Models\DnmClient;
use App\Models\Worksheet;

Class NewDNMClientService implements LogInterface
{
    private $dnmService;
    private $client;
    private $worksheet;
    private $dnmClient;
    private $response;
    private const OOPS = 'Что-то пошло не так, не могу отправить клиента в ДНМ.';
    private const ENTYTYPE = 'КЛИЕНТ';

    use LogTrait;
    
    public function __construct()
    {
        $this->dnmService = DNM::init();
    }



    public function getEntyId() :string
    {
        return $this->client->id;
    }



    public function getEntyType() : string
    {
        return self::ENTYTYPE;
    }



    /**
     * Записать в своей системе данные о клиенте из ДНМ
     */
    private function write(array $data) :void
    {
        $this->dnmClient->fill([
            'client_id' => $this->client->id,
            'dnm_id' => $data['id']
        ])->save();
    }



    /**
     * Подготовить данные для отправки в ДНМ
     */
    private function fill() : array
    {
        if($this->client->isPerson())
            return $this->fillPhysic();
        return $this->fillCompany();
    }



    /**
     * Подготовить ЮрЛицо
     */
    private function fillPhysic() : array
    {
        $confirm = 1;
        $personal = 1;

        return [
            'name'          => $this->client->firstname ?? 'Неизвестно',
            'last_name'     => $this->client->lastname,
            'middle_name'   => $this->client->fathername,
            'phone_hash'    => $this->client->phones->count() ? sha1($this->client->phones[0]) : '',
            'sex'           => $this->client->sex->dnm_id ?? '',
            'code'          => (string)$this->client->id,
            'type'          => $this->client->getDnmTypeClient() ?? '',
            'email'         => $this->client->email ? $this->client->email->first()->email : '',
            'address'       => $this->client->passport ? $this->client->passport->address : '',
            'birthday'      => $this->client->passport ? $this->client->passport->birthday : '',                
            'client_confirm_communication'  => $confirm,
            'may_process_personal_data'     => $personal,
            'phones'        => [
                [
                    'type'      => 1,
                    'number'    => $this->client->phones->first()->phone,
                ]
            ]
        ];
    }



    /**
     * Подготовить ФизЛицо
     */
    private function fillCompany() : array
    {
        $confirm = 1;
        $personal = 1;

        $client = $this->worksheet->subclients->where('client_type_id', 1)->first();

        $client->load('phones');
        
        return [
            'code'                  => (string)$this->client->id,
            'company_name'          => (string)$this->client->company_name,
            'type'                  => $this->client->getDnmTypeClient() ?? '',
            'company_address'       => $this->client->zone->name ?? '',
            "name"                  => $client->firstname,
            'last_name'             => $client->lastname,
            'middle_name'           => $client->fathername,
            "company_legal_form"    => "ООО",
            "company_address"       => "Сыктывкар",
            "email"                 => "",
            "company_email"         => "",
            "phones" => [
                [
                "type" => 2,
                "number" => $client ? $client->phones->first()->phone : '',
                ],
            ],
            'client_confirm_communication'  => $confirm,
            'may_process_personal_data'     => $personal,
        ];
    }



    /**
     * Проверить существование клиента в днм
     */
    public function check() : bool
    {
        $response = $this->dnmService->sendGet('/api/client/', ['code' => $this->client->id]);
        
        if ($response->ok() && count($response->json())) 
        {   
            $this->write($response->json()[0]);
            return 1;
        }

        return 0;
    }



    /**
     * Создать клиента в днм
     */
    private function create() : bool
    {
        $data = $this->fill();

        $response = $this->dnmService->sendPost('/api/client', $data);

        $this->response = $response;

        if ($response->getStatusCode() == 201) {
            $this->write($response->json());
            return 1;
        }

        return 0;
    }



    /**
     * Изменить клиента в днм
     */
    private function update() : bool
    {
        $data = $this->fill();
        
        $response = $this->dnmService->sendPut('/api/client/' . $this->dnmClient->dnm_id, $data);

        $this->response = $response;
        
        if ($response->getStatusCode() == 200) {
            $this->write($response->json());
            return 1;
        }

        return 0;
    }



    /**
     * Сохранение
     */
    public function save(Client $client, Worksheet $worksheet) : void
    {
        $this->client = $client;

        $this->worksheet = $worksheet;

        $this->dnmClient = DnmClient::where('client_id', $this->client->id)->first() ?? new DnmClient();
        
        $result = match($this->check()){
            true => $this->update(),
            false => $this->create(),
            default => throw new \Exception(self::OOPS),
        };
        
        $this->log($result);
    }
}