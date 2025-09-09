<?php

namespace App\Http\Requests\Car\Color;

use Illuminate\Foundation\Http\FormRequest;

class ColorImageRequest extends FormRequest
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
        $requiredFile = 'required|file';

        if(request()->method('PATCH'))
            $requiredFile = 'sometimes|file';
        
        return [
            'bodywork' => 'required|numeric',
            'image' => $requiredFile,
            'color_id' => 'required|numeric'
        ];
    }
}
