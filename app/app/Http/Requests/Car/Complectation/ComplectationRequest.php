<?php

namespace App\Http\Requests\Car\Complectation;

use Illuminate\Foundation\Http\FormRequest;

class ComplectationRequest extends FormRequest
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
            'name' => 'required',
            'mark_id' => 'required',
            'vehicle_type_id' => 'required',
            'body_work_id' => 'required',
            'factory_id' => 'required',
            'motor_driver_id' => 'required',
            'motor_transmission_id' => 'required',
            'motor_type_id' => 'required',
            'power' => 'required',
            'size' => 'required',
            'file' => 'sometimes|file|mimes:pdf',
            'brand_id' => 'required',
            'alias_id' => 'required',
        ];
    }
}
