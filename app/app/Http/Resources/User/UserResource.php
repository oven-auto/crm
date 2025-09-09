<?php

namespace App\Http\Resources\User;

use App\Helpers\String\StringHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->name,
            'email' => $this->email,
            'lastname' => $this->lastname,
            'phone' => StringHelper::phoneMask($this->phone),
            'role' => $this->role->name,
            'trash' => $this->deleted_at ? 1 : 0,
        ];
    }
}
