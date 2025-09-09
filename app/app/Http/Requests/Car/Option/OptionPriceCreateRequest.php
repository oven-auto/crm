<?php

namespace App\Http\Requests\Car\Option;

use Illuminate\Foundation\Http\FormRequest;

class OptionPriceCreateRequest extends FormRequest
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
            'option_id'     => 'required',
            'price'         => 'required',
            'begin_at'      => 'required'
        ];
    }
}
