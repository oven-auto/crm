<?php

namespace App\Models\Worksheet\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMServiceContract extends Model
{
    use HasFactory;

    public $table = 'wsm_service_contracts';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'begin_at' => 'date',
        'register_at' => 'date',
    ];



    public function decorator()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'decorator_id');
    }



    public function manager()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'manager_id');
    }
}
