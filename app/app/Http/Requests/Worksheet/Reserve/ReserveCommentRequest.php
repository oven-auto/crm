<?php

namespace App\Http\Requests\Worksheet\Reserve;

use Illuminate\Foundation\Http\FormRequest;

class ReserveCommentRequest extends FormRequest
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
            'reserve_id' => 'required'
        ];
    }
}
