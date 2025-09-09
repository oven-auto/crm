<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\SortInterface;
use App\Models\Traits\Createable;

class Property extends Model implements SortInterface
{
    use HasFactory, Createable;

    protected $fillable = ['name'];

    public $timestamps = false;
}
