<?php

namespace App\Models;

use app\Models\Interfaces\CarableInterface;
use App\Models\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCar extends Model implements CommentInterface, CarableInterface
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = ['color', 'brand', 'mark', 'transmission', 'drive', 'type', 'bodywork', ];



    public function writeComment(array $data)
    {
        ClientComment::create($data);
    }



    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id')->withDefault();
    }



    public function mark()
    {
        return $this->hasOne(\App\Models\Mark::class, 'id', 'mark_id')->withDefault();
    }



    public function bodywork()
    {
        return $this->hasOne(\App\Models\BodyWork::class, 'id', 'body_work_id')->withDefault();
    }



    public function client()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'client_id')->withDefault();
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }



    public function editor()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'editor_id')->withDefault()->withTrashed();
    }



    public function transmission()
    {
        return $this->hasOne(\App\Models\MotorTransmission::class,'id', 'motor_transmission_id')->withDefault();
    }



    public function drive()
    {
        return $this->hasOne(\App\Models\MotorDriver::class,'id', 'motor_driver_id')->withDefault();
    }



    public function driver()
    {
        return $this->hasOne(\App\Models\MotorDriver::class,'id', 'motor_driver_id')->withDefault();
    }



    public function type()
    {
        return $this->hasOne(\App\Models\MotorType::class,'id', 'motor_type_id')->withDefault();
    }



    public function color()
    {
        return $this->hasOne(\App\Models\Color::class,'id', 'color_id')->withDefault();
    }



    public function vehicle()
    {
        return $this->hasOne(\App\Models\VehicleType::class, 'id', 'vehicle_type_id')->withDefault();
    }



    public function dnm()
    {
        return $this->hasOne(\App\Models\DnmClientCar::class, 'client_car_id', 'id')->withDefault();
    }



    /**
     * Пометить авто клиента как не актуальный
     */
    public function setNonActual()
    {
        $this->fill(['actual' => 0])->save();
    }
}
