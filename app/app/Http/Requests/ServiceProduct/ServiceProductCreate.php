<?php

namespace App\Http\Requests\ServiceProduct;

use App\Http\DTO\ServiceProduct\ServiceProductDTO;
use Illuminate\Foundation\Http\FormRequest;

class ServiceProductCreate extends FormRequest
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
            'name'          => 'required|string',
            'description'   => 'nullable|string',
            'price'         => 'nullable|integer',
            'duration'      => 'nullable|integer',
            'group_id'      => 'nullable|integer',
            'appeal_ids'    => 'required|array',
            'appeal_ids.*'  => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required'         => 'Поле название обязательно для заполнения',
            'price.integer'         => 'Поле цена может должно быть целым числом (стоимость в руб.)',
            'duration.integer'      => 'Поле продолжительность должно быть целым числом (кол-во минут)',
            'group_id.integer'      => 'Поле группа должно быть ссылкой на группу',
            'appeal_ids.required'   => 'Поле обращение обязательно для заполнения',
            'appeal_ids.array'      => 'Поле обращение должно быть массивом',
        ];
    }



    public function getDTO()
    {
        return ServiceProductDTO::fromArray($this->all());
    }
}
