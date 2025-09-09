<?php

namespace App\Http\Requests\Car\Marker;

use Illuminate\Foundation\Http\FormRequest;

class MarkerCreateRequest extends FormRequest
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
            'name' => 'required',
            'text_color' => 'required',
            'body_color' => 'required',
            'description' => 'sometimes'
        ];
    }
}
