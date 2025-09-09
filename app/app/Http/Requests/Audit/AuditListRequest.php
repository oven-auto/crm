<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

class AuditListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trash' => 'sometimes',
            'chanels' => 'sometimes|array',
            'appeals' => 'required|array',
            'ids' => 'sometimes|array',
        ];
    }



    public function messages()
    {
        return [
            'appeals.required' => 'Цель обращения не указана.',
        ];
    }
}
