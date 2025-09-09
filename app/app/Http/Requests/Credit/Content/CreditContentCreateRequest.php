<?php

namespace App\Http\Requests\Credit\Content;

use App\Http\DTO\Credit\CreditContentCreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreditContentCreateRequest extends FormRequest
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
        return CreditContentCreateDTO::fromArray($this->all());
    }
}
