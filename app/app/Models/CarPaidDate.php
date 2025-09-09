<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPaidDate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $casts = [
        'date_at' => 'datetime'
    ];



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function getDateAttribute()
    {
        if($this->date_at)
            return $this->date_at->format('d.m.Y');
        return '';
    }
}
