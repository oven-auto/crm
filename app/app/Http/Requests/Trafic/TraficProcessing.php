<?php

namespace App\Http\Requests\Trafic;

use Illuminate\Foundation\Http\FormRequest;

class TraficProcessing extends FormRequest
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
            'record'    => 'required|file|mimes:wav',
            'audit'     => 'required|file|mimes:pdf',
            'result'    => 'required'
        ];
    }
}
