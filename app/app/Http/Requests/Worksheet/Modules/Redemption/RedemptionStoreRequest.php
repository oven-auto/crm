<?php

namespace App\Http\Requests\Worksheet\Modules\Redemption;

use Illuminate\Foundation\Http\FormRequest;

class RedemptionStoreRequest extends FormRequest
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
            'client_car_id' => 'required|numeric',
            'car_sale_sign_id' => 'required|numeric',
            'expectation' => 'nullable|string',
            'redemption_type_id' => 'required|numeric'
        ];
    }
}
