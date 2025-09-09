<?php

namespace App\Http\Requests\Client;

use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class ClientCarRequest extends FormRequest
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
        $action = $this->action ?? 0;
        //$action = 0;
        //$this->query->remove('action');
        //$this->request->remove('action');

        if($action == 0)
            $params = 'nullable';
        else
            $params = [
                Rule::requiredIf($action == 1),
                'numeric',
                'min:1'
            ];

        if($action == 0)
            $size = 'nullable';
        else
            $size = [
                Rule::requiredIf($action == 1),
                'string',
                'min:1'
            ];

        return [
            'brand_id' => 'required|integer',
            'mark_id' => 'required|integer',
            'body_work_id' => $params,
            'year' => [
                Rule::requiredIf($action == 1),
                'nullable',
                'digits:4',
            ],
            'odometer' => [
                Rule::requiredIf($action == 1),
                'nullable',
                'integer',
                'min:1'
            ],
            'register_plate' => 'nullable|string|max:12',
            'vin' => 'nullable|string|size:17',
            'color_id' => $params,
            'motor_driver_id' => $params,
            'motor_power' => $params,
            'motor_size' => $size,
            'motor_transmission_id' => $params,
            'motor_type_id' => $params,
            'vehicle_type_id' => $params,
        ];
    }

    public function messages()
    {
        return [
            'brand_id.required' => 'Бренд автомобиля обязателен',
            'brand_id.integer' => 'Бренд указан не верно, либо не указан вовсе',
            'mark_id.required' => 'Модель автомобиля обязательна',
            'mark_id.integer' => 'Модель указана не верно, либо не указана вовсе',
            'body_work_id.integer' => 'Кузов указан не верно',
            'year.digits' => 'Год выпуска должен быть числом, состоящим из 4 символов',
            'odometer.integer' => 'Пробег должен быть числом',
            'vin.size' => 'VIN состоит из 17 символов',

            'odometer.*' => 'Пробег указан не верно',
            'year.*' => 'Год выпуска указан не верно',
            'vehicle_type_id.*' => 'Тип ТС не указан',
            'motor_type_id.*' => 'Тип мотора не указан',
            'motor_transmission_id.*' => 'Тип трансмиссии не указан',
            'motor_size.*' => 'Объем мотора указан неверно',
            'motor_power.*' => 'Мощность мотора указана неверно',
            'motor_driver_id.*' => 'Тип привода не указан',
            'color_id.*' => 'Цвет не указан',
            'body_work_id.*' => 'Тип кузова не указан',
        ];
    }
}
