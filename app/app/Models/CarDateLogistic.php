<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDateLogistic extends Model
{
    use HasFactory;

    /**
     * В ЭТОЙ ТАБЛИЧКЕ 2 ТРИГЕРА, НА СОЗДАНИЕ, И НА УДАЛЕНИЕ, Которые МЕНЯЮТ СТАТУС МАШИНЫ
     */

    protected $guarded = [];

    protected $with = ['state'];

    public $casts = [
        'created_at'    => 'datetime', 
        'updated_at'    => 'datetime', 
        'date_at'       => 'datetime'
    ];

    /**RELATIONS */

    /**AUTHOR */
    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withTrashed();
    }



    /**
     * LOGISTIC STATE
     */
    public function state()
    {
        return $this->hasOne(\App\Models\LogisticState::class, 'system_name', 'logistic_system_name');
    }
}
