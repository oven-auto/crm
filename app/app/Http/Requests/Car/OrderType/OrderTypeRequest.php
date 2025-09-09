<?php

namespace App\Http\Requests\Car\OrderType;

use Illuminate\Foundation\Http\FormRequest;

class OrderTypeRequest extends FormRequest
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
            'text_color' => 'required',
            //'body_color' => 'required',
            'description' => 'required|string',
        ];
    }
}
