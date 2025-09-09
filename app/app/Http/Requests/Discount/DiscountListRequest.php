<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"journal"}
 * )
 */
class DiscountListRequest extends FormRequest
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
     * @OA\Property(
     *  property="journal", 
     *  type="string", 
     *  format="string", 
     *  description="Тип журнала, значения для подстановки: newcar, oldcar, service, parts",
     *  enum={"newcar","oldcar", "service", "parts"}
     * ),
     * @OA\Property(
     *  property="ids", format="array", type="array", description="Выбранные ID", @OA\Items(type="integer", example={1,2,3})
     * ),
     * @OA\Property(
     *  property="type", format="array", type="array", description="Тип скидки",  @OA\Items(type="integer", example={1,2,3}))
     * ),
     * @OA\Property(
     *  property="brand_id", format="integer", type="integer", description="Бренд авто", example="55")
     * ),
     * @OA\Property(
     *  property="mark_id", format="integer", type="integer", description="Модель авто", example="55")
     * ),
     * @OA\Property(
     *  property="input", format="string", type="string", description="INPUT")
     * ),
     * @OA\Property(
     *  property="returned", format="string", type="string", description="Возвращаемые", enum={"yes", "no"}, example="yes")
     * ),
     * @OA\Property(
     *  property="checked", format="string", type="string", description="Проверенная", enum={"yes", "no"}, example="yes")
     * ),
     * @OA\Property(
     *  property="isbase", format="string", type="string", description="Есть оснавание", enum={"yes", "no"}, example="yes")
     * ),
     * @OA\Property(
     *  property="isreparation", format="string", type="string", description="Есть возмещение", enum={"yes", "no"}, example="yes")
     * )
     * @OA\Property(
     *  property="sale_interval", format="array", type="array", description="Интервал даты продажы",  @OA\Items(type="string", example="12.05.2024, 15.05.2024")
     * ),
     * @OA\Property(
     *  property="fact_interval", format="array", type="array", description="Интервал даты возмещения", @OA\Items(type="string", example="12.05.2024, 15.05.2024")
     * ),
     */
    public function rules()
    {
        return [
            'journal' => 'required|in:newcar, oldcar, service, parts',
            'ids' => 'sometimes|array',
            'type' => 'sometimes|array',
            'input' => 'sometimes|string',
            'returned' => 'sometimes|in:yes,no',
            'checked' => 'sometimes|in:yes,no',
            'brand_id' => 'sometimes|numeric',
            'mark_id' => 'sometimes|array',
            'isbase' => 'sometimes|in:yes,no',
            'isreparation' => 'sometimes|in:yes,no',
            'sale_interval' => 'sometimes|array',
            'fact_interval' => 'sometimes|array',
        ];
    }
}