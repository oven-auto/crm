<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClientEventExecutor extends Pivot
{
    use HasFactory;

    protected $table = 'client_event_executors';

    public static function boot()
    {
        parent::boot();

        static::saved(function($item){

        });

        static::deleted(function($item){

        });
    }
}
