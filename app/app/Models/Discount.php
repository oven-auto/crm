<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model implements CommentInterface
{
    use HasFactory, Filterable;

    protected $guarded = [];

    protected $with = ['modulable', 'type', 'author', 'sum', 'reparation', 'reparation_date', 'base', 'check'];



    public function writeComment(array $data)
    {
        WsmReserveComment::create($data);    
    }



    public function check()
    {
        return $this->hasOne(DisckountCheck::class, 'discount_id', 'id');
    }



    public function modulable()
    {
        return $this->morphTo();
    }



    public function type()
    {
        return $this->hasOne(\App\Models\DiscountType::class, 'id', 'discount_type_id')->withTrashed();
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function sum()
    {
        return $this->hasOne(\App\Models\DiscountSum::class, 'discount_id', 'id');
    }



    public function reparation()
    {
        return $this->hasOne(\App\Models\DiscountReparation::class, 'discount_id', 'id');
    }



    public function reparation_date()
    {
        return $this->hasOne(\App\Models\DiscountReparationDate::class, 'discount_id', 'id');
    }



    public function base()
    {
        return $this->hasOne(\App\Models\DiscountBase::class, 'discount_id', 'id');
    }



    public function worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'id', 'worksheet_id');
    }



    public function scopeWorksheetRelation(Builder $builder)
    {
        $builder->with(['worksheet' => function ($worksheetBuilder) {
            $worksheetBuilder->with(['client']);
        }]);
    }



    public function getStatus()
    {
        return $this->check->status;
    }



    public function changeStatus()
    {
        $this->check->fill([
            'discount_id' => $this->id,
            'author_id' => auth()->user()->id,
            'status' => abs(( $this->check->status ?? 0) - 1),
        ])->save();
    }
}
