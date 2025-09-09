<?php

namespace App\Http\Requests\Car\Complectation;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"name", "ids", "code", "mark_id", "brand_id", "trash", "status", "insale", "input", "car"}
 * )
 */ 
class ComplectationListRequest extends FormRequest
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
     * @OA\Property(format="string", description="Название комплектации", property="name", type="string"),
     * @OA\Property(format="array", description="Выбранные ID [1,2,3,4]", property="ids", type="array", 
     *      @OA\Items(
     *          type="integer",
     *          example={1,2}
     *      )
     * ),
     * @OA\Property(format="string", description="Код комплектации", property="code", type="string"),
     * @OA\Property(format="array", description="Выбранные модели [1,2,3,4]", property="mark_id", type="array", 
     *      @OA\Items(
     *          type="integer",
     *          example={1,2}
     *      )
     * )),
     * @OA\Property(format="array", description="Выбранные бренды [1,2,3,4]", property="brand_id", type="array", @OA\Items(
     *          type="integer",
     *          example={1,2}
     *      )
     * )),
     * @OA\Property(format="boolean", description="Удаленные", property="trash", type="boolean"),
     * @OA\Property(format="string", description="Статус <trash|active|all>", property="status", type="string"),
     * @OA\Property(format="string", description="По статусам продаж <sold|sale>", property="insale", type="string"),
     * @OA\Property(format="string", description="input поле", property="input", type="string"),
     * @OA\Property(format="string", description="Кнопка для вывода комплектаций с уведомлялками <totrash|towork|tochange>", property="action", type="string"),
     */
    public function rules()
    {
        return [
            'ids'           => 'sometimes|array',
            'name'          => 'sometimes|string',
            'code'          => 'sometimes|string',
            'mark_id'       => 'sometimes|array',
            'brand_id'      => 'sometimes|array',
            'trash'         => 'sometimes',
            'status'        => 'sometimes|in:all,trash,active',
            'insale'        => 'sometimes|in:sold,sale',
            'input'         => 'sometimes|string',
            'car'           => 'sometimes|array',
            'action'        => 'sometimes|string',
        ];
    }
}
