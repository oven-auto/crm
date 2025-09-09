<?php

namespace App\Http\Requests\Trafic;

use App\Http\DTO\Trafic\CreateTraficDTO;
use Illuminate\Foundation\Http\FormRequest;

class TraficCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'trafic_brand_id'       => 'required',
            'trafic_section_id'     => 'required',
            'trafic_appeal_id'      => 'required',
            'author_id'             => 'sometimes',
            'begin_at'              => 'sometimes',
            'comment'               => 'sometimes',
            'created_at'            => 'sometimes',
            'email'                 => 'sometimes',
            'end_at'                => 'sometimes',
            'fathername'            => 'sometimes|string',
            'firstname'             => 'sometimes|string',
            'lastname'              => 'sometimes|string',
            'person_type_id'        => 'sometimes|integer',
            'phone'                 => 'sometimes|string',
            'time'                  => 'sometimes|string',                
            'trafic_chanel_id'      => 'sometimes|integer',
            'trafic_interval'       => 'sometimes|integer',
            'manager_id'            => 'sometimes|integer',
            'trafic_sex_id'         => 'sometimes|integer',
            'trafic_zone_id'        => 'sometimes|integer',
            'trafic_need_id'        => 'sometimes|array',
            'trafic_need_id.*.id'   => 'string',

            'inn' => 'sometimes|string',
            'client_type_id' => 'sometimes|integer',
            'company_name' => 'sometimes|string',

                
        ];
    }



    public function messages()
    {
        return [
            'trafic_brand_id.required' => 'Поле салон обязательно для заполнения',
            'trafic_section_id.required' => 'Поле подразделение обязательно для заполнения',
            'trafic_appeal_id.required' => 'Поле цель обращения обязательно для заполнения',
        ];
    }



    public function getDTO()
    {
        return CreateTraficDTO::fromArray($this->all());
    }
}
