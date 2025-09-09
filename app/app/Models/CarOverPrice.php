<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarOverPrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * RELATIONS
     */

     /**
      * AUTHOR
      */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }
}
