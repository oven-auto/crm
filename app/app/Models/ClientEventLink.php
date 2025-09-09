<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientEventLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }

    public function event()
    {
        return $this->hasOne(\App\Models\ClientEvent::class, 'id', 'event_id')->withDefault();
    }
}
