<?php

namespace App\Models;

use app\Models\Interfaces\CarableInterface;
use App\Models\Traits\Filterable;
use App\Models\Worksheet\Service\WSMServiceCar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class UsedCar extends Model implements CarableInterface
{
    use HasFactory, Filterable;

    protected $guarded = [];

    /**
     * RELATIONS
     */



    public function service(): MorphOne
    {
        return $this->morphOne(WSMServiceCar::class, 'carable');
    }



    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id');
    }



    public function mark()
    {
        return $this->hasOne(\App\Models\Mark::class, 'id', 'mark_id');
    }



    public function bodywork()
    {
        return $this->hasOne(\App\Models\BodyWork::class, 'id', 'body_work_id');
    }



    public function color()
    {
        return $this->hasOne(\App\Models\Color::class, 'id', 'color_id');
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function agent()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'agent_id');
    }



    public function driver()
    {
        return $this->hasOne(\App\Models\MotorDriver::class, 'id', 'motor_driver_id');
    }



    public function transmission()
    {
        return $this->hasOne(\App\Models\MotorTransmission::class, 'id', 'motor_transmission_id');
    }



    public function type()
    {
        return $this->hasOne(\App\Models\MotorType::class, 'id', 'motor_type_id');
    }



    public function redemption()
    {
        return $this->hasOne(\App\Models\WSMRedemptionCar::class, 'id', 'wsm_redemption_car_id');
    }



    public function vehicle()
    {
        return $this->hasOne(\App\Models\VehicleType::class, 'id', 'vehicle_type_id');
    }



    /**
     * SCOPES
     */

    public function scopeFullLazyLoad(Builder $builder)
    {
        $builder->with([
            'vehicle', 'type', 'transmission', 'driver', 'agent', 'author', 'color', 'bodywork', 'mark', 'brand'
        ]);
    }
}
