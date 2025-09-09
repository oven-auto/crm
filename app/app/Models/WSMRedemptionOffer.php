<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMRedemptionOffer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'wsm_redemption_offers';

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id' ,'author_id')->withDefault()->withTrashed();
    }
}
