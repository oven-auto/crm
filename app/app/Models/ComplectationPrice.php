<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplectationPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public $casts = [
        'created_at' => 'datetime', 
        'updated_at' => 'datetime', 
        'begin_at' => 'datetime', 
        'deleted_at' => 'datetime'
    ];



    /**
     * RELATIONs
     */

    /**
     * AUTHOR
     */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }



    public function current()
    {
        return $this->hasOne(\App\Models\ComplectationCurrentPrice::class, 'id', 'id');
    }
}
