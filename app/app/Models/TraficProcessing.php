<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TraficProcessing extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function writeComment(array $data)
    {
        return TraficComment::create($data);
    }

    public function getProcentAttribute()
    {
        return $this->result.'%';
    }

    public function getStatusResultAttribute()
    {
        if($this->status == 0)
            return 'Не пройден';
        return 'Пройден';
    }

    public function getFile($param)
    {
        if(isset($this->$param) && Storage::disk('public')->exists($this->$param))
            return asset('storage/'.$this->$param) . '?' . date('dmyhm');
    }

    public function standart()
    {
        return $this->hasOne(\App\Models\AuditStandart::class, 'id','audit_standart_id')->withDefault();
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id' ,'user_id')->withDefault()->withTrashed();
    }

    public function trafic()
    {
        return $this->hasOne(\App\Models\Trafic::class,'id', 'trafic_id');
    }
}
