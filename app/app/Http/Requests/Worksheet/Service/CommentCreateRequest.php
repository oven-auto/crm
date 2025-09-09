<?php

namespace App\Http\Requests\Worksheet\Service;

use App\Http\DTO\Worksheet\Service\CreateCommentServiceDTO;
use Illuminate\Foundation\Http\FormRequest;

class CommentCreateRequest extends FormRequest
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
            'worksheet_id' => 'required|integer',
            'text' => 'required|string'
        ];
    }



    public function getDTO()
    {
        return CreateCommentServiceDTO::fromArray($this->all());
    }
}
