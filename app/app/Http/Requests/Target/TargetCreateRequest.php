<?php

namespace App\Http\Requests\Target;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"journal"}
 * )
 */
class TargetCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }



    /**
     * @OA\Property(property="brand_id",  example="5",  format="integer", type="integer", description="Бренд")),
     * @OA\Property(property="date_at",     format="03.2025", type="string", description="Дата (месяц год)")),
     * @OA\Property(property="amount",      format="integer", type="integer", description="Количество")),
     
     
     * @OA\Property(
     *  property="marks", format="array", type="object", example="[[id : 1, amount : 100], [id : 1, amount : 100]]", description="Модели [[id : 1, amount : 100], ...]")
     * ),
     */
    public function rules(): array
    {
        return [
            'brand_id'          => 'required',
            'date_at'           => 'required|date_format:m.Y',
            'amount'            => 'required|numeric',
            'marks'             => 'required|array',
            'marks.0'           => 'required|array',
            'marks.*.id'        => 'numeric',
            'marks.*.amount'    => 'numeric'
        ];
    }
}
