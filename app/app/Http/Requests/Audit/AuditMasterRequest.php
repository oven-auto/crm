<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

class AuditMasterRequest extends FormRequest
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
            'trafic_id'     => 'required',
            'audit_id'      => 'required',
            'result'        => 'sometimes|array',
            'result.positive.*'    => 'numeric',
            'result.negative.*'    => 'numeric',
            'result.neutral.*'     => 'numeric',
        ];
    }
}
