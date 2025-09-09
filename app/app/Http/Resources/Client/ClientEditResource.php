<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $contacts = [];

        foreach($this->phones as $key => $item)
            $contacts[$key]['phone'] = [
                'number' => $item->phone,
                'empty_phone' => $item->empty_phone,
            ];

        foreach($this->emails as $key => $item)
            $contacts[$key]['email'] = $item->email;

        if($this->id)
            return [
                'data' => [
                    'id'                        => $this->id,
                    'firstname'                 => $this->firstname ? $this->firstname : null,
                    'lastname'                  => $this->lastname ? $this->lastname : null,
                    'fathername'                => $this->fathername ? $this->fathername : null,
                    'client_type_id'            => $this->client_type_id,
                    'trafic_sex_id'             => $this->trafic_sex_id,
                    'trafic_zone_id'            => $this->trafic_zone_id,
                    'birthday_at'               => ($this->passport->birthday_at) ? $this->passport->birthday_at->format('d.m.Y') : null,
                    'address'                   => $this->passport->address,
                    'driver_license_issue_at'   => $this->passport->driver_license_issue_at ? $this->passport->driver_license_issue_at->format('d.m.Y') : null,
                    'driving_license'           => $this->passport->driving_license,
                    'passport_issue_at'         => $this->passport->passport_issue_at ? $this->passport->passport_issue_at->format('d.m.Y') : null,
                    'serial_number'             => $this->passport->serial_number,
                    'phones'                    => $this->phones->map(function($item){
                        return [
                            'phone' => $item->phone_mask,
                            'empty_phone' => $item->empty_phone
                        ];
                    }),
                    'emails' =>                 $this->emails->map(function($item){
                        return $item->email;
                    }),
                    'company_name'              => $this->company_name,
                    'inn'                       => $this->inn->number,
                    'url'                       => $this->url,
                    'created_at'                => $this->created_at ? $this->created_at->format('d.m.Y (H:i)') : '',
                    'files'                     => $this->files_count,
                    'links'                     => $this->links_count,
                    'form_owner_id'             => $this->passport->form_owner_id,
                ],
                'success' => 1,
            ];
        else
            return [
                'success' => 0,
                'message' => 'Такой Клиент не существует'
            ];
    }
}
