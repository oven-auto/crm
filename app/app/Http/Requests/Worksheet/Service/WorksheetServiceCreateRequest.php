<?php

namespace App\Http\Requests\Worksheet\Service;

use App\Http\DTO\Worksheet\Service\CreateServiceDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      
 * )
 */ 
class WorksheetServiceCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    
    /**
     * @OA\Property(description="Машина",     property="car",   type="array", @OA\Items(type="integer", example={"id" = "2", "type" = "new"})),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="ID финансовой услуги",     
     *      property="service",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="ID провайдера",     
     *      property="provider",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="Стоимость",     
     *      property="cost",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="ID тип оплаты",     
     *      property="payment",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="ID РЛ",     
     *      property="worksheet",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Упрощнный растет",     
     *      property="simple",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Расторжение",     
     *      property="close",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      format="string",    
     *      description="Номр договора",     
     *      property="number",        
     *      type="string"
     * ),
     * 
     * @OA\Property(
     *      format="string",    
     *      description="Дата начала",     
     *      property="begin_at",        
     *      type="string"
     * ),
     * 
     * @OA\Property(
     *      format="string",    
     *      description="Дата оформления",     
     *      property="register_at",        
     *      type="string"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="ID оформителя",     
     *      property="decorator",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="ID продовца",     
     *      property="manager",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="Премия",     
     *      property="award",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Зачисление премии",     
     *      property="award_complete",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Удержание",     
     *      property="deduction",        
     *      type="boolean"
     * ),
     */
    public function rules(): array
    {
        return [
            'car'               => 'array',
            'car.type'          => 'string|required|in:new,client,used',
            'car.id'            => 'required|numeric',
            'service'           => 'required',
            'provider'          => 'sometimes|nullable',
            'cost'              => 'sometimes',
            'payment'           => 'required',
            'worksheet'         => 'required',
            'simple'            => 'required',
            'close'             => 'sometimes',

            'number'            => 'sometimes',
            'begin_at'          => 'required_with:number',
            'register_at'       => 'required_with:number',
            'decorator'         => 'required_with:number',
            'manager'           => 'required_with:number',

            'award'             => 'sometimes',
            'award_complete'    => 'required_with:award',

            'deduction'         => 'sometimes',
        ];
    }



    public function getDTO() : CreateServiceDTO
    {
        return CreateServiceDTO::fromArray($this->validated());
    }
}
