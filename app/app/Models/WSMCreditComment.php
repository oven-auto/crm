<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMCreditComment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $table = 'wsm_credit_comments';

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id');
    }
}
