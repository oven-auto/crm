<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WsmReserveContractCost extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    public function reserve()
    {
        return $this->hasOne(\App\Models\WsmReserveNewCarContract::class, 'id', 'contract_id');
    }
}
