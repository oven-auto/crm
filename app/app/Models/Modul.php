<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function appeals()
    {
        return $this->belongsToMany(\App\Models\Appeal::class, 'modul_appeals', 'appeal_id');
    }
}
