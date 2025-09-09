<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditAssist extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function trafic()
    {
        return $this->hasOne(\App\Models\Trafic::class, 'id', 'trafic_id');
    }



    public function audit()
    {
        return $this->hasOne(\App\Models\Audit\Audit::class, 'id', 'audit_id');
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }
}
