<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarOrderType extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**RELATIONS */

    /**
     * AUTHOR
     */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    /**
     * TYPE
     */
    public function type()
    {
        return $this->hasOne(\App\Models\OrderType::class, 'id', 'order_type_id')->withDefault();
    }



    /**
     * CAR
     */
    public function car()
    {
        return $this->hasOne(\App\Models\Car::class, 'id', 'car_id');
    }
}
