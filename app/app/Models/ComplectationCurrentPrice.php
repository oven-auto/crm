<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplectationCurrentPrice extends Model
{
    use HasFactory;

    public $dates = ['begin_at'];

    protected $with = ['curprice'];

    protected $casts = [
        'begin_at' => 'date:d.m.Y',
        'created_at' => 'date:d.m.Y',
        'updated_at' => 'date:d.m.Y',
    ];

    public function curprice()
    {
        return $this->hasOne(\App\Models\ComplectationPrice::class, 'id', 'id');
    }
}
