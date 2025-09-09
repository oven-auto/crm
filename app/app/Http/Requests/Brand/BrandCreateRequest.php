<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Route;
use Illuminate\Validation\Rule;

class BrandCreateRequest extends FormRequest
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
        $brand = Route::current()->parameter('brand');

        return [
            'name'=>[
                'required',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($brand ? $brand->id : ''),
            ],
            'icon' => 'required|image',
            'brand_color' => 'required|string',
            'font_color' => 'required|string',
        ];
    }
}
