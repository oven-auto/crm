<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractOptionPrice extends Model
{
    use HasFactory;

    protected $with = ['price'];



    public function price()
    {
        return $this->hasOne(\App\Models\OptionPrice::class, 'id', 'option_price_id');
    }



    public function getPrice()
    {
        return $this->price->price;
    }
}
