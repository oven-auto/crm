<?php

namespace App\Http\Requests\Worksheet\Reserve;

use Illuminate\Foundation\Http\FormRequest;

class ContractSaveRequest extends FormRequest
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
        $method = request()->method();

        return [
            'pdkp_offer_at' => 'sometimes',
            'pdkp_delivery_at' => 'sometimes',
            'pdkp_decorator_id' => 'sometimes',
            'pdkp_closed_at' => 'sometimes',

            'dkp_offer_at' => 'sometimes',
            'dkp_decorator_id' => 'sometimes',
            'dkp_closed_at' => 'sometimes',

            'reserve_id' => $method == 'post' ? 'required' : 'sometimes'
        ];
    }
}
