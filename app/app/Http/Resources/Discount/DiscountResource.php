<?php

namespace App\Http\Resources\Discount;

use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\User\UserSmallResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'returnable' => $this->returnable,
            'author' => new UserSmallResource($this->author),
            'salon' => new CompanyResource($this->salon),
            'modul' => [
                'id' => $this->modul->id,
                'name' => $this->modul->name,
            ],
            'created_at' => $this->attributesToArray()['created_at'] ?? '',
            'updated_at' => $this->attributesToArray()['updated_at'] ?? '',
            'trash'         => $this->deleted_at ? 1 : 0,
        ];
    }
}
