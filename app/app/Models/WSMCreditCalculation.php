<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMCreditCalculation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $table = 'wsm_credit_calculations';

    public $timestamps = false;
}
