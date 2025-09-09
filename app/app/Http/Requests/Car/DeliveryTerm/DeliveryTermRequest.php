<?php

namespace App\Http\Requests\Car\DeliveryTerm;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"name", "text_color", "description"}
 * )
 */ 
class DeliveryTermRequest extends FormRequest
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

    /**
     *          @OA\Property(
     *              property="name", type="string", format="1", description="Название"
     *          ),
     *          @OA\Property(
     *              property="text_color", type="string", format="1", description="Цвет текста"
     *          ),
     *          @OA\Property(
     *              property="description", type="string", format="1", description="Описание"
     *          ),
     *          @OA\Property(
     *              property="begin_period", type="integer", format="1", description="Количество дней до начала кредитного периода"
     *          ),
     *          @OA\Property(
     *              property="end_period", type="integer", format="1", description="Количество дней до окончания кредитного периода"
     *          ),
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'text_color' => 'required',
            'description' => 'required|string',
            'begin_period' => 'sometimes|numeric',
            'end_period' => 'sometimes|numeric',
        ];
    }
}
