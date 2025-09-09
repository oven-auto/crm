<?php

namespace App\Http\Requests\Worksheet;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"trafic_id"}
 * )
 */ 
class WorksheetStoreRequest extends FormRequest
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
     * @OA\Property(property="trafic_id",type="integer",format="integer",description="ID Трафика")
     */
    public function rules()
    {
        return [
            'trafic_id' => 'required|exists:trafics,id'
        ];
    }
}
