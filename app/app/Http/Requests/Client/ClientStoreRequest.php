<?php

namespace App\Http\Requests\Client;

use App\Helpers\String\StringHelper;
use App\Http\DTO\Client\ClientDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ClientStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

  
    
    public function rules()
    {
        $arr = [];

        if(request()->has('phones'))
            foreach(request()->phones as $item)
                if(isset($item['phone']))
                    $arr[] = [
                        'phone' => StringHelper::onlyLettersAndNumbers($item['phone']),
                        'empty_phone' => $item['empty_phone']
                    ];
        request()->merge(['phones' => $arr]);

        return [
            'client_type_id'            => 'required|integer',
            'trafic_sex_id'             => 'nullable|integer',
            'trafic_zone_id'            => 'nullable|integer',
            'firstname'                 => 'nullable|string',
            'lastname'                  => 'nullable|string',
            'fathername'                => 'nullable|string',
            'emails'                    => 'nullable|array',

            'emails.*'                  => [
                                            'string',
                                            Rule::unique('client_emails','email')->ignore(request()->client ? request()->client->id : '', 'client_id'),
            ],
            
            'phones'                    => 'required_if:client_type_id,1|array',
            'phones.0.phone'            => 'required_if:client_type_id,1|string',
            'phones.0.empty_phone'      => 'required_if:client_type_id,1|integer',
            'phones.*.phone'            => [
                                            Rule::unique('client_phones','phone')->ignore(request()->client ? request()->client->id : '', 'client_id'),
            ],

            'url'                       => 'nullable',
            'inn'                       => [
                                            'nullable',
                                            'required_if:client_type_id,2',
                                            'string',
                                            Rule::unique('client_inns','number')->ignore(request()->client ? request()->client->id : '', 'client_id'),
            ],
            'company_name'              => 'nullable|required_if:client_type_id,2|string',
            'form_owner_id'             => 'sometimes|numeric|nullable',

            'address'                   => 'nullable|string',
            'birthday_at'               => 'nullable|date',
            'passport_issue_at'         => 'nullable|date',
            'serial_number'             => 'nullable|regex:([0-9]{4}\s{1}[0-9]{6})',
            'driver_license_issue_at'   => 'nullable|date',
            'driving_license'           => 'nullable|regex:([0-9]{4}\s{1}[0-9]{6})',
        ];
    }



    public function messages()
    {
        $client = Route::current()->parameter('client');
        return [
            'firstname.string' => 'Имя может состоять только из букв',
            'lastname.string' => 'Фамилия может состоять только из букв',
            'fathername.alpha' => 'Отчество может состоять только из букв',
            'trafic_sex_id.required' => 'Не указан тип клиента (Физ./Юр. лицо)',
            'birthday_at.date' => 'Формат даты дня рождения DD.MM.YYYY',
            'driver_license_issue_at.date' => 'Формат даты выдачи вод. уд DD.MM.YYYY',
            'passport_issue_at.date' => 'Формат даты выдачи паспорта DD.MM.YYYY',
            'driving_license.regex' => 'Формат серии и номера вод. уд. XXXX XXXXXX',
            'serial_number.regex' => 'Формат серии и номера паспорта XXXX XXXXXX',
        ];
    }



    public function getDTO()
    {
        return ClientDTO::fromArray($this->validated());
    }
}



