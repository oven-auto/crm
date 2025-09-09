<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCalculation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'cost', 'company_award', 'design_award', 'sale_award'
    ];
}
