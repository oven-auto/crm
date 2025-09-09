<?php

namespace App\Http\Requests\Worksheet\Action;

use Illuminate\Foundation\Http\FormRequest;

class StoreActionRequest extends FormRequest
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
            'begin_at' => 'required|date_format:d.m.Y H:i',
            'end_at' => 'required|date_format:d.m.Y H:i',
            'task_id' => 'required|numeric',
            'text' => 'required|string'
        ];
    }
}
