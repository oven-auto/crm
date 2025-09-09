<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProlongation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'prolongation', 'template_id'
    ];



    public function template()
    {
        return $this->hasOne(ClientEventTemplate::class, 'id', 'template_id');
    }
}
