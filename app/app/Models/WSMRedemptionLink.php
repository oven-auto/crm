<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMRedemptionLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'wsm_redemption_links';

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id' ,'author_id')->withDefault()->withTrashed();
    }
}
