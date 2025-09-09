<?php

namespace App\Models\Audit;

use App\Repositories\Audit\Interfaces\AuditSortInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditSubQuestion extends Model implements AuditSortInterface
{
    use HasFactory;

    protected $guarded = [];

    public function answers()
    {
        return $this->hasMany(AuditSubAnswer::class, 'sub_id', 'id');
    }
}
