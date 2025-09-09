<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'date_at',
        'author_id',
        'amount',
    ];



    protected $casts = [
        'date_at' => 'datetime'
    ];



    public function marks()
    {
        return $this->belongsToMany(\App\Models\Mark::class, 'target_marks', 'target_id')->withPivot('amount');
    }



    public function brand()
    {
        return $this->hasOne(\App\Models\Brand::class, 'id', 'brand_id');
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }
}
