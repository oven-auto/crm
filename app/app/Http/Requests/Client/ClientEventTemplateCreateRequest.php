<?php

namespace App\Http\Requests\Client;

use App\Http\DTO\Client\ClientEvent\ClientEventTemplateDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      
 * )
 */ 
class ClientEventTemplateCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    /**
     * @OA\Property(
     *      description="Исполнители",     
     *      property="executors",   
     *      type="array", 
     *      @OA\Items(type="integer", example={1,2})
     * ),
     * 
     * @OA\Property(
     *      description="Заголовок",     
     *      property="title",   
     *      type="string", 
     *      format="string"
     * ),
     * 
     * @OA\Property(
     *      description="Comment",     
     *      property="comment",   
     *      type="string", 
     *      format="string"
     * ),
     * 
     * @OA\Property(
     *      description="group",     
     *      property="group",   
     *      type="integer", 
     *      format="integer"
     * ),
     * 
     * @OA\Property(
     *      description="type",     
     *      property="type",   
     *      type="integer", 
     *      format="integer"
     * ),
     * 
     * @OA\Property(
     *      description="name",     
     *      property="name",   
     *      type="string", 
     *      format="string"
     * ),
     * 
     * @OA\Property(
     *      description="begin",     
     *      property="begin",   
     *      type="integer", 
     *      format="integer"
     * ),
     * 
     * @OA\Property(
     *      description="author",     
     *      property="author",   
     *      type="integer", 
     *      format="integer"
     * ),
     * 
     * @OA\Property(
     *      description="resolve",     
     *      property="resolve",   
     *      type="boolean", 
     *      format="boolean"
     * ),
     * 
     * @OA\Property(
     *      description="process",     
     *      property="process",   
     *      type="integer", 
     *      format="integer"
     * ),
     * 
     * @OA\Property(
     *      description="ССылки",     
     *      property="links",   
     *      type="array", 
     *      @OA\Items(type="string", example={"erer","erer"})
     * )
     */
    public function rules(): array
    {
        return [
            'title'             => 'required|string',
            'group'             => 'required|integer',
            'type'              => 'required|integer',
            'comment'           => 'required|string',
            'name'              => 'required|string',
            'executors'         => 'sometimes|array',
            'executors.*'       => 'integer',
            'begin'             => 'integer|required',
            'author'            => 'required|integer',
            'resolve'           => 'required|bool',
            'process'           => 'required',
            'links'             => 'sometimes|array',
            'links.*'           => 'string',
        ];
    }



    public function getDTO()
    {
        return ClientEventTemplateDTO::fromArray($this->all());
    }
}
