<?php

namespace App\Http\Resources\For1c;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientFindResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dnm_id' => $this->dnm->dnm_id ?? '',
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'fathername' => $this->fathername,
            'sex' => $this->sex,
            'type' => $this->type,
            'emails' => $this->emails->map(function($item){
                return $item->email;
            }),
            'phones' => $this->phones->map(function($item){
                return $item->phone;
            }),
            'inn'                   => $this->inn->id ? $this->inn->inn : '',
            'passport' => $this->passport->id ? [
                'passport_number'   => $this->passport->serial_number,
                'passport_issue'    => $this->passport->passport_issue_at ? $this->passport->passport_issue_at->foramt('d.m.Y') : '',
                'birthday'          => $this->passport->birthday_at ? $this->passport->birthday_at->format('d.m.Y') : '',
                'address'           => $this->passport->address,
            ] : [],
        ];
    }
}
