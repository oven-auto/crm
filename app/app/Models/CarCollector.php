<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarCollector extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['collector'];



    public function collector()
    {
        return $this->hasOne(\App\Models\Collector::class, 'id', 'collector_id')->withDefault();
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }
}
