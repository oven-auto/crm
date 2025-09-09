<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarProvider extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected $with = ['provider'];

    /**
     * RELATION
     */

    /**
     * PROVIDER
     */
    public function provider()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'provider_id');
    }



    /**
     * METHODS
     */

    /**
     * ATTR
     */
    public function getCutNameAttribute()
    {
        return $this->provider->full_name;
    }
}
