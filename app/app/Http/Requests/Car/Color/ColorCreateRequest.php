<?php

namespace App\Http\Requests\Car\Color;

use App\Http\DTO\Car\Color\CreateColorDTO;
use Illuminate\Foundation\Http\FormRequest;

class ColorCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'brand_id' => 'required',
            'mark_id' => 'required',
            'base_id' => 'required',
            'name' => 'required'
        ];
    }



    public function getDTO()
    {
        return CreateColorDTO::fromArray($this->all());
    }
}
