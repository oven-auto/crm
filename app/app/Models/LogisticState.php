<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticState extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];



    /**
     * RELATIONS
     */

    public function carstate()
    {
        return $this->hasOne(\App\Models\CarState::class, 'logistic_system_name', 'system_name')->withDefault();
    }
}
