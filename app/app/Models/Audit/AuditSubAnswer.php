<?php

namespace App\Models\Audit;

use App\Repositories\Audit\Interfaces\AuditSortInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditSubAnswer extends Model implements AuditSortInterface
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    
}
