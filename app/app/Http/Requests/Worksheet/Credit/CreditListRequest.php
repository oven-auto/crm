<?php

namespace App\Http\Requests\Worksheet\Credit;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      
 * )
 */ 
class CreditListRequest extends FormRequest
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
     *      description="ID Оформитель",     
     *      property="decorator",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      format="string",    
     *      description="Сортировка",     
     *      property="sort - calculate_asc,calculate_desc,register_asc,register_desc",        
     *      type="string"
     * ),
     * 
     * @OA\Property(
     *      format="integer",    
     *      description="ИД РЛ",     
     *      property="worksheet_id",        
     *      type="integer"
     * ),
     * 
     * @OA\Property(
     *      description="Тактика",     
     *      property="tactic",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      description="Заемщик",     
     *      property="creditor",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Брокерская",     
     *      property="is_broker",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Оформлена да/нет",     
     *      property="has_register",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      description="Периода регистрации",     
     *      property="registration",   
     *      type="array", 
     *      @OA\Items(type="string", example={"28.08.2554","15.03.3000"})
     * ),
     * 
     * @OA\Property(   
     *      format="boolean",
     *      description="Расторгнут",     
     *      property="has_close",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Начисление",     
     *      property="has_award",        
     *      type="boolean"
     * ),
     * 
     * @OA\Property(
     *      description="Статус заявки",     
     *      property="status",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      description="Статус кредита",     
     *      property="state",   
     *      type="array", 
     *      @OA\Items(type="string", example={"work"})
     * ),
     * 
     * @OA\Property(
     *      description="Автор",     
     *      property="author",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      format="boolean",    
     *      description="Актуально",     
     *      property="is_actual",        
     *      type="boolean"
     * ),
     */
    public function rules(): array
    {
        return [
            'is_actual' => 'sometimes|boolean',

            'ids' => 'sometimes|array',
            'ids.*' => 'integer',

            'sort' => 'sometimes|string',

            'search' => 'sometimes|string',

            'worksheet_id' => 'sometimes|integer',

            'tactic' => 'sometimes|array',
            'tactic.*' => 'integer',

            'is_broker' => 'sometimes|boolean',
            
            'creditor' => 'sometimes|array',
            'creditor.*' => 'integer',

            'has_register' => 'sometimes|boolean',

            'registration' => 'sometimes|array',
            'registration.*' => 'date_format:d.m.Y',

            'status' => 'sometimes|array',
            'status.*' => 'integer',

            'state' => 'sometimes|array',
            'state.*' => 'string',

            'has_close' => 'sometimes|boolean',

            'has_award' => 'sometimes|boolean',

            'decorator' => 'sometimes|array',
            'decorator.*' => 'integer',

            'author' => 'sometimes|array',
            'author.*' => 'integer',
        ];
    }
}
