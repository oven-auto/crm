<?php

namespace App\Models\Worksheet\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMServiceAward extends Model
{
    use HasFactory;

    public $table = 'wsm_service_awards';

    public $timestamps = false;

    protected $guarded = [];
}
