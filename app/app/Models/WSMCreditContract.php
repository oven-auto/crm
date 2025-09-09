<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMCreditContract extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $table = 'wsm_credit_contracts';

    public $timestamps = false;

    public $casts = [
        'register_at' => 'date'
    ];



    public function decorator()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'decorator_id');
    }
}
