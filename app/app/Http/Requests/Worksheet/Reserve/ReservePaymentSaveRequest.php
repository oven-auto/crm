<?php

namespace App\Http\Requests\Worksheet\Reserve;

use Illuminate\Foundation\Http\FormRequest;

class ReservePaymentSaveRequest extends FormRequest
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
            'reserve_id' => 'required',
            'payment_id' => 'required',
            'amount' =>     'required|numeric',
            'date_at'   =>  'required',
        ];
    }
}
