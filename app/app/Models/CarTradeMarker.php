<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarTradeMarker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;



    public function marker()
    {
        return $this->hasOne(\App\Models\TradeMarker::class, 'id', 'trade_marker_id')->withDefault();
    }
}
