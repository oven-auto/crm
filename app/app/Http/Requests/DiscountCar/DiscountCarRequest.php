<?php

namespace App\Http\Requests\DiscountCar;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCarRequest extends FormRequest
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
            'returnable' => 'boolean|required',
            'salon' => 'required|numeric',
            'modul' => 'required|numeric'
        ];
    }
}
