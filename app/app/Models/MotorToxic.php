<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Createable;

class MotorToxic extends Model
{
    use HasFactory,Createable;

    public $timestamps = false;

    protected $guarded = [];
}
