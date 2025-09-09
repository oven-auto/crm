<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMCreditState extends Model
{
    use HasFactory;

    private const STATES = [
        'work', 'issue', 'miss', 'close'
    ];

    public $table = 'wsm_credit_states';
}
