<?php

namespace App\Models\Traits;

trait PriceChangeable
{
    public function changePrice($price)
    {
        $this->price = $price;
        $this->save();
    }
}
