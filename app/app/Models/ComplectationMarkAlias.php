<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplectationMarkAlias extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function alias()
    {
        return $this->hasOne(\App\Models\MarkAlias::class, 'id', 'mark_alias_id')->withDefault();
    }
}
