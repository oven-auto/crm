<?php

namespace App\Models\Worksheet\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMServiceCar extends Model
{
    use HasFactory;

    public $table = 'wsm_service_cars';

    protected $guarded = [];

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



    public static function getArrayType()
    {
        return array_values(self::TYPE);
    }



    public function getType()
    {
        return self::TYPE[$this->carable_type];
    }



    public static function getModelName(string $key)
    {
        return array_search($key, self::TYPE);
    }
}
