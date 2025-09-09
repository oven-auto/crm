<?php

namespace App\Models;

use App\Helpers\Url\WebUrl;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complectation extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date:d.m.Y',
        'updated_at' => 'date:d.m.Y',
    ];

    /* СВЯЗИ */

    /**
     * АВТОР
     */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    /**
     * МОДЕЛЬ И ИЗ НЕЕ ЖЕ БРЕНД
     */
    public function mark()
    {
        return $this->hasOne(\App\Models\Mark::class, 'id', 'mark_id')->with('brand');
    }



    /**
     * ТИП ТС
     */
    public function vehicle()
    {
        return $this->hasOne(\App\Models\VehicleType::class, 'id', 'vehicle_type_id');
    }



    /**
     * ТИП КУЗОВА
     */
    public function bodywork()
    {
        return $this->hasOne(\App\Models\BodyWork::class, 'id', 'body_work_id');
    }



    /**
     * МОТОР
     */
    public function motor()
    {
        return $this->hasOne(\App\Models\Motor::class, 'id', 'motor_id')
            ->with(['transmission', 'driver', 'type']);
    }



    /**
     * СТРАНА ПРОИЗВОДСТВА
     */
    public function factory()
    {
        return $this->hasOne(\App\Models\Factory::class, 'id', 'factory_id');
    }



    /**
     * ФАИЛЫ
     */
    public function file()
    {
        return $this->hasOne(\App\Models\ComplectationFile::class, 'complectation_id', 'id');
    }



    /**
     * ИСТОРИЯ
     */
    public function history()
    {
        return $this->hasMany(\App\Models\ComplectationHistory::class, 'complectation_id', 'id');
    }



    /**
     * LAST HISTORY
     */
    public function last_history()
    {
        return $this->hasOne(\App\Models\ComplectationHistory::class, 'complectation_id', 'id')->orderBy('id', 'DESC')->withDefault();
    }



    /**
     * GET FILE URL
     */
    public function getUrlFile()
    {
        if ($this->file)
            return WebUrl::make_link($this->file->file, false);
        return '';
    }



    public function current_price()
    {
        return $this->hasOne(\App\Models\ComplectationCurrentPrice::class, 'complectation_id', 'id')->withDefault();
    }



    public function prices()
    {
        return $this->hasMany(\App\Models\ComplectationPrice::class, 'complectation_id', 'id')->orderBy('id', 'DESC');
    }



    public function alias()
    {
        return $this->hasOne(\App\Models\ComplectationMarkAlias::class, 'complectation_id', 'id');
    }



    public function cars()
    {
        return $this->hasMany(\App\Models\Car::class, 'complectation_id', 'id')
            ->select(['cars.*']);            
    }



    public function scopeActiveCountCars(Builder $builder)
    {
        $builder->withCount(['cars as active_car' => function($query) {
            $query
                ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.car_id', 'cars.id')
                ->leftJoin('wsm_reserve_sales', 'wsm_reserve_sales.reserve_id', 'wsm_reserve_new_cars.id')
                ->leftJoin('wsm_reserve_issues', 'wsm_reserve_issues.reserve_id', 'wsm_reserve_new_cars.id')
                ->whereNull('wsm_reserve_sales.reserve_id')
                ->whereNull('wsm_reserve_issues.reserve_id');
                //->whereNull('wsm_reserve_new_cars.deleted_at');
        }]);
    }



    public function scopeSaledCountCars(Builder $builder)
    {
        $builder->withCount(['cars as saled_cars' => function($query) {
            $query
                ->leftJoin('wsm_reserve_new_cars', 'wsm_reserve_new_cars.car_id', 'cars.id')
                ->leftJoin('wsm_reserve_sales', 'wsm_reserve_sales.reserve_id', 'wsm_reserve_new_cars.id')
                ->leftJoin('wsm_reserve_issues', 'wsm_reserve_issues.reserve_id', 'wsm_reserve_new_cars.id')
                ->whereNotNull('wsm_reserve_sales.reserve_id')
                ->whereNotNull('wsm_reserve_issues.reserve_id');
                //->whereNull('wsm_reserve_new_cars.deleted_at');
        }]);
    }



    public function scopeFullRelations(Builder $builder)
    {
        $builder->with([
            'alias' => function($alias){
                $alias->with(['alias']);
            }, 
            'current_price', 'file', 'factory', 'motor', 'bodywork', 'vehicle', 'mark', 'author'
        ]);
    }



    /**
     * ACCESSORS
     */

    public function getPriceAttribute()
    {
        return $this->current_price->price ?? 0;
    }



    public function getPriceDateAttribute()
    {
        return $this->current_price->begin_at ? $this->current_price->begin_at->format('d.m.Y') : '';
    }



    public function saveAlias($aliasId)
    {
        $this->alias()->updateOrCreate([
            'complectation_id' => $this->id,
            'mark_alias_id' => $aliasId
        ]);
    }
}
