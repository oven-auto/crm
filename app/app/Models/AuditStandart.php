<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditStandart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public const STATUSES = [
        ['id'=>1, 'name' => 'Пройден'],
        ['id'=>2, 'name' => 'Не пройден'],
        ['id'=>3, 'name' => 'Отсутсвует'],
    ];
}
