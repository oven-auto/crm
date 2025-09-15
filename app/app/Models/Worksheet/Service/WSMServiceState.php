<?php

namespace App\Models\Worksheet\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSMServiceState extends Model
{
    use HasFactory;

    private const STATES = [
        'work', 'issue', 'miss', 'close'
    ];

    public $table = 'wsm_service_states';



    public function getStateNameAttribute()
    {
        return match($this->state) {
            'work' => 'В работе',
            'issue' => 'Оформлен',
            'miss' => 'Упущен',
            default => '',
        };
    }
}
