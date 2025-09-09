<?php

namespace App\Http\Requests\Worksheet\Service;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      
 * )
 */ 
class ServiceListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }



    /**
     * @OA\Property(
     *      description="ID",     
     *      property="ids",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      format="string",    
     *      description="Поиск",     
     *      property="search",        
     *      type="string"
     * ),
     * 
     * @OA\Property(
     *      format="string",    
     *      description="Тип авто",     
     *      property="car_type",        
     *      type="string"
     * ),
     * 
     * @OA\Property(
     *      description="ID Продавец",     
     *      property="manager",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      description="ID Оформитель",     
     *      property="decorator",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      description="Интервал продажи",     
     *      property="sale_interval",   
     *      type="array", 
     *      @OA\Items(type="integer", example={"12.12.2025","11.11.2026"})
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="Категория",     
     *      property="category",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="ID Финуслуги",     
     *      property="service",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      format="string",    
     *      description="Сортировка",     
     *      property="sort - calculate_asc,calculate_desc,begin_asc,begin_desc,register_asc,register_desc",        
     *      type="string"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Есть событие",     
     *      property="has_event",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Есть кредит",     
     *      property="has_credit",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Актуально",     
     *      property="is_actual",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      description="Способ оплаты",     
     *      property="payment",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     */
    public function rules(): array
    {
        return [
            'ids' => 'sometimes|array',
            'search' => 'sometimes|string',
            'car_type' => 'sometimes|array',
            'car_type.*' => 'in:new,used,client',
            
            'manager' => 'sometimes|array',
            'decorator' => 'sometimes|array',
            
            'category' => 'sometimes|numeric',
            'service' => 'sometimes|array',
            'service.*' => 'integer',

            'sort' => 'sometimes|string|in:calculate_asc,calculate_desc,begin_asc,begin_desc,register_asc,register_desc',
            'sale_interval' => 'sometimes|array|min:2',
            'sale_interval.0' => 'string|date_format:d.m.Y',
            'sale_interval.1' => 'string|date_format:d.m.Y',

            'has_registration' => 'sometimes|boolean',
            'registration' => 'sometimes|array',
            'registration.*' => 'date_format:d.m.Y',

            'begin' => 'sometimes|array',
            'begin.*' => 'date_format:d.m.Y',

            'has_close' => 'sometimes|boolean',

            'has_award' => 'sometimes|boolean',

            'provider' => 'sometimes|array',
            'provider.*' => 'integer',

            'state' => 'sometimes|array',
            'state.*' => 'in:work,issue,miss',

            'has_event' => 'sometimes|boolean',
            'payment' => 'sometimes|array',
            'payment.*' => 'integer',
            'in_credit' => 'sometimes|boolean',
            'is_actual' => 'sometimes|boolean',
        ];
    }
}
