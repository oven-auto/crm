<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMCreditDeduction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $table = 'wsm_credit_deductions';

    public $timestamps = false;
}
