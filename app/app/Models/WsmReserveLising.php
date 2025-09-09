<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WsmReserveLising extends Model
{
    use HasFactory;
    
    public function reserve() 
    {
        return $this->hasOne(\App\Models\WsmReserveNewCar::class, 'id','reserve_id');
    }
    
    
    
    public function client()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'client_id');
    }
}
