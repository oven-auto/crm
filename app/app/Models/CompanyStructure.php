<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyStructure extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $with = ['structure'];

    public function structure()
    {
        return $this->hasOne(\App\Models\Structure::class,'id','structure_id')->withDefault();
    }
}
