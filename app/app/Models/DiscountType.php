<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'created_at'            => 'datetime:d.m.Y',
        'updated_at'            => 'datetime:d.m.Y',
        'deleted_at'            => 'datetime:d.m.Y',
    ];

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function salon()
    {
        return $this->hasOne(\App\Models\Company::class, 'id', 'salon_id')->withDefault();
    }



    public function modul()
    {
        return $this->hasOne(\App\Models\Modul::class, 'id', 'modul_id')->withDefault();
    }
}
