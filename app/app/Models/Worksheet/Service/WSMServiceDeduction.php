<?php

namespace App\Models\Worksheet\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMServiceDeduction extends Model
{
    use HasFactory;

    public $table = 'wsm_service_deductions';

    public $timestamps = false;

    protected $guarded = [];
}
