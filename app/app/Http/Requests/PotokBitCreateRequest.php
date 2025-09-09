<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PotokBitCreateRequest extends FormRequest
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
            'firstname' => 'sometimes|string',
            'lastname' => 'sometimes|string',
            'phone' => 'required|digits:11',
            'link' => 'required',
            'comment' => 'required|string',
            //'appeal' => 'required|in:sale,ransom',
        ];
    }
}
