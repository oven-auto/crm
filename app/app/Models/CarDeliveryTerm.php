<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDeliveryTerm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['term'];

    protected $casts = [
        'date_at' => 'datetime'
    ];

    /**
     * RELATION
     */



    public function term()
    {
        return $this->hasOne(\App\Models\DeliveryTerm::class, 'id', 'delivery_term_id')->withDefault();
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }
}
