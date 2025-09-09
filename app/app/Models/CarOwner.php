<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarOwner extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function car()
    {
        return $this->hasOne(\App\Models\Car::class, 'id', 'car_id');
    }



    public function client()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'client_id');
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }
}
