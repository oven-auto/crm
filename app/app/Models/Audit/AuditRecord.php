<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditRecord extends Model
{
    use HasFactory;

    protected $fillable = ['master_id', 'file'];
}
