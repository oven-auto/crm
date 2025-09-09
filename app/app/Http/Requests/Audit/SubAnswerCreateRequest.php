<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

class SubAnswerCreateRequest extends FormRequest
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
            'sub_id' => 'required|exists:audit_sub_questions,id',
            'text' => 'required',
        ];
    }



    public function messages()
    {
        return [
            'sub_id.required' => 'Поле "Идентификатор подвопроса" не указано.',
            'sub_id.exists' => 'Поле "Идентификатор подвопроса" не существует.',
            'text.required' => 'Поле "Текст" не указано.',
        ];
    }
}
