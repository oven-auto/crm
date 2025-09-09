<?php

namespace App\Http\Requests\Director;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"name", "acronym", "vehicle"}
 * )
 */ 
class DirectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Property(example="[01.10.2024,22.10.2024],[01.10.2024,22.10.2024]", format="array", description="Интервал", property="intervals",   type="array", @OA\Items())),
     * @OA\Property(example="[1,2,3]", format="array", description="Цель обращения", property="appeal_ids",  type="array", @OA\Items())),
     * @OA\Property(example="[1,2,3]", format="array", description="Компания", property="company_ids", type="array", @OA\Items())),
     * @OA\Property(example="[1,2,3]", format="array", description="Канал", property="chanels",     type="array", @OA\Items())),
     */
    public function rules(): array
    {
        return [
            'intervals' => 'required|array',
            'intervals.*' => 'array|size:2',
            'intervals.*.*' => 'date_format:d.m.Y',
            'appeal_ids' => 'sometimes|array',
            'company_ids' => 'sometimes|array',
            'chanels' => 'sometimes|array'
        ];
    }
}
