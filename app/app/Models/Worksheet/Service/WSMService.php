<?php

namespace App\Models\Worksheet\Service;

use App\Models\Car;
use App\Models\ClientCar;
use app\Models\Interfaces\CarableInterface;
use App\Models\Interfaces\EventInterface;
use App\Models\Interfaces\HasActivityCarInterface;
use App\Models\Traits\Filterable;
use App\Models\UsedCar;
use App\Models\Worksheet;
use App\Services\Worksheet\Service\Actuality\ActualityService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMService extends Model implements EventInterface, HasActivityCarInterface
{
    use HasFactory, Filterable;

    public $table = 'wsm_services';

    protected $fillable = [
        'worksheet_id', 'provider_id', 'service_id', 'simple', 'cost', 'payment_id', 'author_id', 'close'
    ];

    protected $with = ['state'];



    public function scopeAllRelation($query)
    {
        $query->with(['credit','state','author','provider','payment','award','contract' => function($q){
            $q->with(['decorator', 'manager']);
        },'deduction', 'car.carable' => function($q){
            $q->with(['brand','mark']);
        }]);
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id');
    }



    public function payment()
    {
        return $this->hasOne(\App\Models\ServicePayment::class, 'id', 'payment_id');
    }



    public function service()
    {
        return $this->hasOne(\App\Models\Service::class, 'id', 'service_id');
    }



    public function provider()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'provider_id');
    }



    public function worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'id', 'worksheet_id');
    }



    public function award()
    {
        return $this->hasOne(WSMServiceAward::class, 'wsm_service_id', 'id');
    }



    public function contract()
    {
        return $this->hasOne(WSMServiceContract::class, 'wsm_service_id', 'id');
    }



    public function deduction()
    {
        return $this->hasOne(WSMServiceDeduction::class, 'wsm_service_id', 'id');
    }



    public function car()
    {
        return $this->hasOne(WSMServiceCar::class, 'wsm_service_id', 'id');
    }



    public function state()
    {
        return $this->hasOne(\App\Models\Worksheet\Service\WSMServiceState::class, 'wsm_service_id', 'id')->withDefault();;
    }



    public function credit()
    {
        return $this->hasOne(\App\Models\WSMCreditService::class, 'wsm_service_id', 'id');
    }



    public function event()
    {
        return $this->hasOne(\App\Models\Worksheet\Service\WSMServiceClientEvent::class, 'wsm_service_id', 'id')->with('event');
    }



    /**
     * METHODS
     */
    public function getStatusAttribure()
    {

    }



    public function getActualityAttribute()
    {
        return ActualityService::init($this)->check();
    }



    public function hasCredit()
    {
        return $this->credit ? 1 : 0;
    }



    public function isAwardCompleted() : bool
    {
        return $this->award->completed ?? false;
    }



    public function isClosed() : bool
    {
        return $this->close ?? false;
    }



    public function getProcentAward()
    {
        if($this->award->sum)
            return round(100 / ($this->cost / $this->award->sum));
        return 0;
    }



    public function getCar() : CarableInterface
    {
        return $this->car->carable;
    }



    public function getWorksheet() : Worksheet
    {
        return $this->worksheet;
    }
}
