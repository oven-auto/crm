<?php

namespace App\Http\Requests\Car\Option;

use Illuminate\Foundation\Http\FormRequest;

class OptionCreateRequest extends FormRequest
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
            'brand_id' => 'required|numeric',
            'name' => 'required|string',
            'code' => 'required|string',
            //'price' => 'required|numeric'
        ];
    }
}
