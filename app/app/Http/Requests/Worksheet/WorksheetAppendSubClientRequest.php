<?php

namespace App\Http\Requests\Worksheet;

use Illuminate\Foundation\Http\FormRequest;

class WorksheetAppendSubClientRequest extends FormRequest
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
            'worksheet_id' => 'required|numeric',
            'client_id' => 'required|numeric',
        ];
    }
}
