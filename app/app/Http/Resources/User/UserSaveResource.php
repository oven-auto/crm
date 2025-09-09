<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSaveResource extends JsonResource
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
            'data' => [
                'trash' => $this->deleted_at ? 1 : 0,
                'id' => $this->id,
                'firstname' => $this->name,
                'email' => $this->email,
                'lastname' => $this->lastname,
                'phone' => $this->phone,
                'role_id' => $this->role_id,
                'tg_token' => $this->tg_token,
                'appeals' => isset($this->appeals) ? $this->appeals->map(function($item){
                    return $item->id;
                }) : [],
                'trafic_appeals' => isset($this->trafic_appeals) ? $this->trafic_appeals->map(function($item){
                    return $item->id;
                }) : [],
                'structures' => isset($this->structures) ? $this->structures->map(function($item){
                    return $item->company_structure_id;
                }) : [],
            ],
            'success' => 1,
            'manual' => 'Для пароля использовать поле password, для поля подтверждения пароля password_confirmation'
        ];
    }
}
