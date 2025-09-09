<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'audit_id'              =>       'required|exists:audits,id',
            'name'                  =>       'required',
            'text'                  =>       'required',
            'weight'                =>       'sometimes|numeric',
            'answers'               =>       'array|required',
            'answers.positive'      =>       'required|accepted',
            'answers.negative'      =>       'sometimes',
            'answers.neutral'       =>       'sometimes',
        ];
    }



    public function messages()
    {
        return [
            'audit_id.required' => 'Поле "Идентификатор аудита" не указано.',
            'audit_id.exists' => 'Поле "Идентификатор аудита" не существует.',
            'name.required' => 'Поле "Название" не указано.',
            'text.required' => 'Поле "Описание" не указано.',
            'weight.numeric' => 'Поле "Вес" должно быть целое число.',
            'is_stoped.numeric' => 'Поле "Если выбран Н\А" должно быть 1 или 0.',
            'answers.required' => 'Поле "Доступные оценки" не указано.',
            'answers.positive.required' => 'Поле "Доступная позитивная оценка" не указано.',
        ];
    }
}
