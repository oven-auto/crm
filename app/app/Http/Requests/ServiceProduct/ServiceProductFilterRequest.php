<?php

namespace App\Http\Requests\ServiceProduct;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    
    public function rules(): array
    {
        return [
            'input' => 'sometimes|string',
            'appeal_ids' => 'sometimes|array',
            'appeal_ids.*' => 'integer',
            'group_ids' => 'sometimes|array',
            'group_ids.*' => 'integer',
        ];
    }
}
