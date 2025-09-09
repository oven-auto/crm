<?php

namespace App\Http\Resources\Trafic;

use App\Models\Trafic;
use Illuminate\Http\Resources\Json\JsonResource;

class TraficEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $showSuperBykovSecurity = (
            $request->has('author_id') || $request->has('manager_id')
        );

        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d.m.Y (H:i)'),
            'updated_at' => $this->updated_at->format('d.m.Y (H:i)'),
            'author' => $this->author->cut_name,
                        
            'zone' => $this->zone->name,

            'chanel' => $this->chanel->name,
            'parent_chanel' => $this->chanel->myparent->name,

            'salon' => $this->salon->name,
            'structure' => isset($this->structure) ? $this->structure->name : '',
            'appeal' => isset($this->appeal) ? $this->appeal->name : '',
            'manager' =>  $this->manager->cut_name,
            'needs' => $this->needs->pluck('name'),
            
            'worksheet' => [
                'id' => $this->worksheet->id,
                'created_at' => $this->worksheet->created_at ? $this->worksheet->created_at->format('d.m.Y H:i') : null,
            ],
            
            'status' => $this->status,
            'processing_at' => $this->processing_at ? $this->processing_at->format('d.m.Y H:i') : ($this->deleted_at ? $this->deleted_at->format('d.m.Y H:i') : ''),
            //'processing' => $this->processing->count() ? true : false, 
            
            
           
            //'processing_at' => $this->audit_master ? $this->audit_master->created_at->format('d.m.Y (H:i)') : ($this->deleted_at ? $this->deleted_at->format('d.m.Y H:i') : ''),
            'processing' => $this->auditmaster ? [
                'complete' => $this->auditmaster->audit->complete,
                'point' => $this->auditmaster->point,
                'completed' => $this->auditmaster->completed
            ] : [],
            
            

            //COMMENT
            'comment' => $this->comment,
            //CLIENT
            'client' => ['id'=>$this->worksheet->client_id],
            'person' => $this->person->name,
            'inn' => $this->inn,
            'company_name' => $this->company_name,
            'phone' => $showSuperBykovSecurity ? $this->formated_phone : $this->phone_mask,
            'email' => $this->email,
            'sex' => $showSuperBykovSecurity ? $this->client_name : ($this->person->name ? $this->person->name : $this->sex->name),
            //CONTROL
            'interval' => $this->interval,
            'begin_at' => $this->begin_at ? $this->begin_at->format('d.m.Y H:i') : '',
            'end_at' => $this->end_at ? $this->end_at->format('d.m.Y H:i') : '',
            //INDICATORS
            'links' =>  $this->links_count,
            'files' => $this->files_count,

            'empty_phone' => $this->client->empty_phone,

            'audit_status' => $this->auditmaster ? $this->auditmaster->status : '',
        ];
    }
}
