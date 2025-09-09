<?php

namespace App\Models;

use app\Models\Interfaces\CarableInterface;
use App\Models\Interfaces\HasActivityCarInterface;
use App\Models\Traits\Filterable;
use App\Models\Worksheet\Service\WSMService;
use App\Services\Worksheet\Service\Actuality\ActualityService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMCredit extends Model implements HasActivityCarInterface
{
    use HasFactory, Filterable;

    protected $guarded = [];

    public $table = 'wsm_credits';



    public function scopeAllRelations($query)
    {
        $query->with([
            'state',
            'worksheet',
            'debtor',
            'tactic',
            'creditor',
            'status',
            'author',
            'award',
            'contract',
            'calculation',
            'deduction',
            'services' => function($serQ){
                $serQ->with([
                    'author',
                    'provider',
                    'payment',
                    'award',
                    'contract' => function($q){
                        $q->with(['decorator', 'manager']);
                    },
                    'deduction', 
                    'car.carable' => function($q){
                        $q->with(['brand','mark']);
                    },
                ]);
            },
            'approximates',
            'car.carable' => function($q){
                $q->with(['brand','mark']);
            },
        ]);
    }



    public function state()
    {
        return $this->hasOne(\App\Models\WSMCreditState::class, 'wsm_credit_id', 'id')->withDefault();
    }



    public function worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'id', 'worksheet_id');
    }



    public function debtor()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'debtor_id');
    }



    public function tactic()
    {
        return $this->hasOne(\App\Models\CreditTactic::class, 'id', 'calculation_type');
    }



    public function creditor()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'creditor_id');
    }



    public function status()
    {
        return $this->hasOne(\App\Models\CreditStatus::class, 'id', 'status_id');
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id');
    }



    public function award()
    {
        return $this->hasOne(\App\Models\WSMCreditAward::class, 'wsm_credit_id', 'id');
    }



    public function contract()
    {
        return $this->hasOne(\App\Models\WSMCreditContract::class, 'wsm_credit_id', 'id');
    }



    public function calculation()
    {
        return $this->hasOne(\App\Models\WSMCreditCalculation::class, 'wsm_credit_id', 'id');
    }



    public function deduction()
    {
        return $this->hasOne(\App\Models\WSMCreditDeduction::class, 'wsm_credit_id', 'id');
    }
    


    public function services()
    {
        return $this->belongsToMany(WSMService::class, 'wsm_credit_services', 'wsm_credit_id', 'wsm_service_id', 'id');
    }



    public function approximates()
    {
        return $this->belongsToMany(Service::class, 'wsm_credit_approximate_services', 'wsm_credit_id', 'service_id', 'id');
    }



    public function car()
    {
        return $this->hasOne(WSMCreditCar::class, 'wsm_credit_id', 'id');
    }



    public function content()
    {
        return $this->belongsToMany(
            \App\Models\CreditContent::class, 
            'wsm_credit_contents', 'wsm_credit_id', 'credit_content_id', 'id', 'id'
        );
    }



    public function creator()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'creator_id');
    }



    public function getActualityAttribute()
    {
        return ActualityService::init($this)->check();
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
