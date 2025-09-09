<?php

namespace App\Models\Worksheet\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMServiceClientEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $table = 'wsm_service_client_events';

    public $timestamps = false;

    public function event()
    {
        return $this->hasOne(\App\Models\ClientEvent::class, 'id', 'client_event_id')->with('lastStatus');
    }
}
