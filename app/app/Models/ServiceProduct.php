<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProduct extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    protected $guarded = [];

    public $timestamps = false;

    public function appeal()
    {
        return $this->hasOne(\App\Models\Appeal::class,'id','appeal_id')->withDefault();
    }



    public function group()
    {
        return $this->hasOne(\App\Models\ProductGroup::class,'id', 'group_id')->withDefault()->withTrashed();
    }



    public function appeals()
    {
        return $this->belongsToMany(\App\Models\Appeal::class, 'service_product_appeals', 'service_product_id', 'appeal_id',  'id', 'id');
    }
}
