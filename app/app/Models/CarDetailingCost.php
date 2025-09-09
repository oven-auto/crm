<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDetailingCost extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['detailing'];

    public $timestamps = false;

    /**
     * RELATIONS
     */

    /**
    * DETAILING
     */
    public function detailing()
    {
        return $this->hasOne(\App\Models\DetailingCost::class, 'id', 'detailing_cost_id');
    }
}
