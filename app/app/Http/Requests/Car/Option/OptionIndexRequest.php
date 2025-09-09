<?php

namespace App\Http\Requests\Car\Option;

use Illuminate\Foundation\Http\FormRequest;

class OptionIndexRequest extends FormRequest
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
            'car_id' => 'sometimes',
            'name' => 'sometimes|numeric',
            'code' => 'sometimes|string',
            'mark_id' => 'sometimes|string',
            'trash' => 'sometimes|numeric',
        ];
    }
}
