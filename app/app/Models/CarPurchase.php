<?php

namespace App\Models;

use App\Helpers\String\StringHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPurchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*RELATIONS*/

    /**
     * AUTHOR
     */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    /**
     * GET METHODs
     */
}
