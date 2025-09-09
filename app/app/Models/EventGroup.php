<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function childrens()
    {
        return $this->hasMany(\App\Models\ClientEvent::class, 'event_group_id', 'id');
    }
}
