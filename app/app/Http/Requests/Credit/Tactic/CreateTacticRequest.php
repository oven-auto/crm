<?php

namespace App\Http\Requests\Credit\Tactic;

use App\Http\DTO\Credit\TacticCreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateTacticRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    

    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'sometimes|string'
        ];
    }



    public function getDTO()
    {
        return TacticCreateDTO::fromArray($this->all());
    }
}
