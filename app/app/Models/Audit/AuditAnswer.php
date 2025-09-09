<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditAnswer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['question_id', 'positive', 'negative', 'neutral'];
}
