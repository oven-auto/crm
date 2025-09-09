<?php

namespace App\Http\Requests\Car\Complectation;

use Illuminate\Foundation\Http\FormRequest;

class ComplectationPriceListRequest extends FormRequest
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
            'complectation_id' => 'sometimes|numeric',
            'car_id' => 'sometimes|numeric'
        ];
    }



    public function messages()
    {
        return [
            'complectation_id.required' => 'Не указана комплектация. Если Вы находитесь в режиме создания комплектации, то сначала сохраните ее.'
        ];
    }
}
