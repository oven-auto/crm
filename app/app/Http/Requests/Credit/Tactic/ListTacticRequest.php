<?php

namespace App\Http\Requests\Credit\Tactic;

use Illuminate\Foundation\Http\FormRequest;

class ListTacticRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    
    public function rules(): array
    {
        return [
            'trash' => 'sometimes'
        ];
    }
}
