<?php

namespace App\Http\Requests\ServiceProduct;

use Illuminate\Foundation\Http\FormRequest;

class ProductGroupCreate extends FormRequest
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
            'name' => 'required|string',
            'sort' => 'nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле название обязательно для заполнения',
            'sort.integer' => 'Поле сортировка должно быть целым числом'
        ];
    }
}
