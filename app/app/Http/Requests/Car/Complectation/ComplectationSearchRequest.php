<?php

namespace App\Http\Requests\Car\Complectation;

use Illuminate\Foundation\Http\FormRequest;

class ComplectationSearchRequest extends FormRequest
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
            'code' => 'required',
            'mark_id' => 'required',
            'id' => 'sometimes'
        ];
    }
}
