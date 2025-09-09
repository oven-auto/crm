<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

class AuditSaveRequest extends FormRequest
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
            'name'          =>      'required',
            'appeal_id'     =>      'required|exists:appeals,id',
            'complete'      =>      'required',
            'bonus'         =>      'required',
            'malus'         =>      'required',
            'chanels'       =>      'required|array',
            'chanels.*'     =>      'required|numeric',
            'award'         =>      'sometimes|numeric',
        ];
    }



    public function messages()
    {
        return [
            'name.required' => 'Поле "Название" не указано.',
            'appeal_id.required' => 'Поле "Цель обращения" не указано.',
            'appeal_id.exists' => 'Поле "Цель обращения" не существует.',
            'complete.required' => 'Поле "Успешный результат" не указано.',
            'bonus.required' => 'Поле "Бонус" не указано.',
            'malus.required' => 'Поле "Малус" не указано.',
            'chanels.required' => 'Поле "Канал трафика" не указано.',
            'chanels.array' => 'Поле "Канал трафика" долеж иметь тип массив.',
        ];
    }
}
