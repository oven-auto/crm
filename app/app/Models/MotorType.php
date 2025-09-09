<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Createable;

class MotorType extends Model
{
    use HasFactory,Createable;

    protected $fillable = ['name', 'acronym'];

    public $timestamps = false;
}
