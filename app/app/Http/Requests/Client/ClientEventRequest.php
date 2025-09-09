<?php

namespace App\Http\Requests\Client;

use App\Http\DTO\Client\ClientEvent\ClientEventCreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class ClientEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

 
    
    public function rules()
    {
        return [
            'client_id'     => 'required',
            'date_at'       => 'required',
            'title'         => 'required',
            'group_id'      => 'required',
            'type_id'       => 'required',
            //'text'          => 'required',
            'executors'     => 'nullable',
            'begin_time'    => 'nullable|date_format:H:i',
            'end_time'      => 'nullable|date_format:H:i',
        ];
    }


    
    public function messages()
    {
        return [
            'date_at.required'  => 'Дата назначения обязательна',
            'title.required'    => 'Заголовок обязателен',
            'group_id.required' => 'Группа обязательна',
            'type_id.required'  => 'Тип обязателен',
            'text.required'     => 'Комментарий обязателен для заполнения',
            'executors.array'   => 'Ответственные внесены не верно',
        ];
    }



    public function getDTO()
    {
        return ClientEventCreateDTO::fromArray($this->all());
    }
}
