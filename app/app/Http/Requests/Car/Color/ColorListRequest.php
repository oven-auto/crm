<?php

namespace App\Http\Requests\Car\Color;

use Illuminate\Foundation\Http\FormRequest;

class ColorListRequest extends FormRequest
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
            'mark_id' => 'required|numeric',
        ];
    }
}
