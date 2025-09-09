<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMCreditCar extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $table = 'wsm_credit_cars';

    public $timestamps = false;

    private const TYPE = [
        'App\Models\UsedCar'    => 'used',
        'App\Models\ClientCar'  => 'client',
        'App\Models\Car'        => 'new',
    ];



    public function carable()
    {
        return $this->morphTo();
    }



    public static function getModelName(string $key)
    {
        return array_search($key, self::TYPE);
    }



    public static function getArrayType()
    {
        return array_values(self::TYPE);
    }



    public function getType()
    {
        return self::TYPE[$this->carable_type];
    }
}
