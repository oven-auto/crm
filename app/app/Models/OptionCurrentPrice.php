<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionCurrentPrice extends Model
{
    use HasFactory;

    protected $with = ['option_price'];

    public function option_price()
    {
        return $this->hasOne(\App\Models\OptionPrice::class, 'id', 'id');
    }
}
