<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientEventTemplate extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'title', 'group_id','type_id', 
        'comment', 'name', 'begin', 
        'executors', 'status', 'author_id', 
        'resolve', 'process_id', 'links', 'editor_id'
    ];



    public function process()
    {
        return $this->hasOne(\App\Models\ClientEventTemplateProcess::class, 'id', 'process_id');
    }



    public function editor()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'editor_id');
    }



    public function getExecutors()
    {
        return User::whereIn('id', json_decode($this->executors))->get()->map(function($item){
            return [
                'id' => $item->id,
                'name' => $item->cut_name,
            ];
        });
    }
}
