<?php

namespace App\Http\Requests\Service;

use App\Repositories\Services\DTO\ServiceDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"name", "category", "provider"}
 * )
 */ 
class ServiceCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

  
    
    /**
     * @OA\Property(format="string",    description="Название",     property="name",        type="string"),
     * @OA\Property(format="integer",   description="Категоря",     property="category",    type="integer"),
     * @OA\Property(description="Поставщик",     property="provider",    type="array", @OA\Items(type="integer", example={"new"})),),
     * 
     * @OA\Property(format="integer",   description="стоимость",     property="cost",   type="integer"),
     * @OA\Property(format="integer",   description="премия компании",     property="company_award",   type="integer"),
     * @OA\Property(format="integer",   description="премия за оформление",     property="design_award",   type="integer"),
     * @OA\Property(format="integer",   description="премия за продажу",     property="sale_award",   type="integer"),
     * @OA\Property(format="integer",   description="Напоминание",     property="duration",   type="integer"),
     * 
     * @OA\Property(description="Применяемость",     property="applicability",   type="array", @OA\Items(type="integer", example={1,2,3})),
     * 
     * @OA\Property(format="boolean",   description="Прологация да/нет",     property="prolongation",   type="boolean"),
     * @OA\Property(format="integer",   description="Шаблон",     property="template",   type="integer"),
     */
    public function rules(): array
    {
        return [
            'category'          => 'required',
            'name'              => 'required|string',
            'providers'         => 'required|array',
            //'cost'              => 'required|min:0',
            'company_award'     => 'nullable',
            'design_award'      => 'nullable',
            'sale_award'        => 'nullable',
            'applicability'     => 'array|required',
            'applicability.*'   => 'in:new,client,used',
            'duration'          => 'nullable',
            'manager'           => 'nullable',

            'prolongation'      => 'sometimes|boolean',
            'template'          => 'sometimes|integer',
        ];
    }



    public function getDTO() : ServiceDTO
    {
        return ServiceDTO::fromArray($this->validated());
    }
}
