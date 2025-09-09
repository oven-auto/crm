<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

class SubQuestionStoreRequest extends FormRequest
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
            'question_id'   => 'required|exists:audit_questions,id',
            'text'          => 'required',
            'multiple'      => 'required',
        ];
    }



    public function messages()
    {
        return [
            'question_id.required' => 'Поле "Идентификатор вопроса" не указано.',
            'question_id.exists' => 'Поле "Идентификатор вопроса" не существует.',
            'text.required' => 'Поле "Текст" не указано.',
            'multiple.required' => 'Поле "Множественный" не указано.',
        ];
    }
}
