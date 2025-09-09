<?php

namespace App\Http\Requests\Worksheet\Reserve;

use Illuminate\Foundation\Http\FormRequest;

class SaleSaveRequest extends FormRequest
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
            'discount_type_id' => 'required',
            'sum' => 'required',
            'reparation' => 'sometimes',
            'reparation_date' => 'sometimes',
            'base' => 'sometimes',
            'reserve_id' => 'required'
        ];
    }
}
