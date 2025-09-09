<?php

namespace App\Http\Requests\Bodywork;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"name", "acronym", "vehicle"}
 * )
 */ 
class BodyworkSaveRequest extends FormRequest
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
     * @OA\Property(format="string", description="Название кузова", property="name", type="string"),
     * @OA\Property(format="string", description="Акроним кузова", property="acronym"),
     * @OA\Property(format="integer", description="Тип ТС", property="vehicle", type="integer"),
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'acronym' => 'required',
            'vehicle' => 'required',
        ];
    }
}
