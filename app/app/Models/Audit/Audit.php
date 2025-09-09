<?php

namespace App\Models\Audit;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $fillable = ['name', 'author_id', 'appeal_id', 'bonus', 'malus', 'complete', 'award'];



    public function questions()
    {
        return $this->hasMany(\App\Models\Audit\AuditQuestion::class, 'audit_id', 'id');
    }



    public function appeal()
    {
        return $this->hasOne(\App\Models\Appeal::class, 'id', 'appeal_id');
    }



    public function chanels()
    {
        return $this->belongsToMany(\App\Models\TraficChanel::class, 'audit_chanels', 'audit_id', 'chanel_id');
    }



    public function author()
    {
        return $this->hasOne(\App\MOdels\User::class, 'id', 'author_id')->withTrashed();
    }



    public function editor()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'editor_id')->withTrashed();
    }
}
