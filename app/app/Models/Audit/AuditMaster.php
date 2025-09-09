<?php

namespace App\Models\Audit;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditMaster extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    protected $guarded = [];

    public const EXCEPTIONS = [
        'delete_wait' => 'Нельзя удалять ожидающий аудит.',
        'restore_not_deleted' => 'Нельзя востановить не удаленный аудит.',
        'find_fail' => 'Аудит не найден.',
        'delete_only_job' => 'Удалять можно только рабочий аудит.',
        'delete_close' => 'Нельзя удалять завершенный аудит.',
        'arbitr_close_only' => 'Аппелировать можно только завершенный аудит.',
        'already_exist' => 'Уже существует мастер для этого аудита в этом трафике.',
        'update_closed' => 'Аудит завершен, его редактирование отразится на результате. Поэтому данная операция отменена. Для редактирования данного аудита необходимо поменять его статус в аппеляция.',
    ];



    /**
     * RELATIONS
     */



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }



    public function trafic()
    {
        return $this->hasOne(\App\Models\Trafic::class, 'id', 'trafic_id')->withTrashed();
    }



    public function audit()
    {
        return $this->hasOne(\App\Models\Audit\Audit::class, 'id', 'audit_id');
    }



    public function record()
    {
        return $this->hasOne(\App\Models\Audit\AuditRecord::class, 'master_id', 'id');
    }



    /**
     * METHODS
     */

    public function isClose()
    {
        return $this->status == 'close' ? 1 : 0;
    }



    public function isWait()
    {
        return $this->status == 'wait' ? 1 : 0;
    }



    public function isArbitr()
    {
        return $this->status == 'arbitr' ? 1 : 0;
    }



    public function isDeleted()
    {
        return $this->deleted_at ? 1 : 0;
    }



    public function closeStatus()
    {
        $this->status = 'close';
        $this->save();
    }



    public function waitSatus()
    {
        $this->status = 'wait';
        $this->save();
    }



    public function arbitrSatus()
    {
        $this->status = 'arbitr';
        $this->save();
    }



    public function getResponseCount()
    {
        $data = json_decode($this->result, 1) ?? [];

        $res = 0;

        array_walk_recursive($data, function($item) use (&$res){
            $res += 1;
        });

        return $res;
    }
}
