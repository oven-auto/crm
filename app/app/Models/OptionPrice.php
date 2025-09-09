<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionPrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $casts = [
        'created_at' => 'date', 
        'updated_at' => 'date', 
        'begin_at' => 'date', 
        'deleted_at' => 'date'
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



    // public function current()
    // {
    //     return $this->hasOne(\App\Models\OptionCurrentPrice::class, 'id', 'id');
    // }
}
