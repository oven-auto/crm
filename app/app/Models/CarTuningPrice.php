<?php

namespace App\Models;

use App\Models\Interfaces\TuningPriceInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarTuningPrice extends Model implements TuningPriceInterface
{
    use HasFactory;

    protected $guarded = [];

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function getCarId(): int
    {
        return $this->car_id;
    }
}
