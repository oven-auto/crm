<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Filterable;
use App\Models\Traits\AttachMethod;
use Illuminate\Support\Facades\DB;

class Worksheet extends Model
{
    use HasFactory, Filterable, AttachMethod;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'close_at' => 'datetime',
    ];

    private const WORKING_STATUSES = [
        'work',
        'check'
    ];



    /**BEGIN SCOPES */
    public function scopeOverdue($query)
    {
        return $query->where('worksheets.status_id', 'work')
            ->where('worksheet_actions.end_at', '<', now());
    }



    public function scopeCheck($query)
    {
        return $query->where('worksheets.status_id', 'check');
    }



    public function scopeLinksCount($query)
    {
        return $query->withCount('links');
    }



    public function scopeFilesCount($query)
    {
        return $query->withCount('files');
    }
    /**END SCOPES */



    public function isClosing()
    {
        if ($this->status_id == 'confirm')
            return true;
        return false;
    }



    public function canIChange()
    {
        $user = auth()->user();

        $appeal = $user->appeals->contains('id', $this->appeal_id);
    }



    public function getCreatedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('d.m.Y') : '';
    }



    public function dnm()
    {
        return $this->hasOne(\App\Models\DnmWorksheet::class, 'worksheet_id', 'id');
    }



    public function client()
    {
        return $this->hasOne(\App\Models\Client::class, 'id', 'client_id')->withDefault();
    }



    public function trafic()
    {
        return $this->hasOne(\App\Models\Trafic::class, 'id', 'trafic_id')->withDefault();
    }



    public function company()
    {
        return $this->hasOne(\App\Models\Company::class, 'id', 'company_id')->withDefault();
    }



    public function structure()
    {
        return $this->hasOne(\App\Models\Structure::class, 'id', 'structure_id')->withDefault();
    }



    public function appeal()
    {
        return $this->hasOne(\App\Models\Appeal::class, 'id', 'appeal_id')->withDefault();
    }



    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }



    public function executors()
    {
        return $this->belongsToMany(\App\Models\User::class, 'worksheet_executors', 'worksheet_id')->withTrashed();
    }



    public function subclients()
    {
        return $this->belongsToMany(\App\Models\Client::class, 'worksheet_sub_clients', 'worksheet_id');
    }



    public function last_action()
    {
        return $this->hasOne(\App\Models\WorksheetAction::class, 'worksheet_id', 'id')->orderBy('id', 'DESC')->withDefault();
    }



    public function actions()
    {
        return $this->hasMany(\App\Models\WorksheetAction::class, 'worksheet_id', 'id')->with(['comments', 'author', 'task']);
    }



    public function links()
    {
        return $this->hasMany(WorksheetLink::class, 'worksheet_id', 'id');
    }



    public function status()
    {
        return $this->hasOne(\App\Models\WorksheetStatus::class, 'slug', 'status_id')->withDefault();
    }



    public function inspector()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'inspector_id')->withDefault()->withTrashed();
    }



    public function isWork()
    {
        if (in_array($this->status_id, self::WORKING_STATUSES))
            return 1;
        return 0;
    }



    public function files()
    {
        return $this->hasMany(\App\Models\WorksheetFile::class, 'worksheet_id', 'id');
    }



    public function redemptions()
    {
        return $this->hasMany(\App\Models\WSMRedemptionCar::class, 'worksheet_id', 'id');
    }



    public function reserves()
    {
        return $this->hasMany(\App\Models\WsmReserveNewCar::class, 'worksheet_id', 'id');
    }







    public function reporters()
    {
        return $this->belongsToMany(\App\Models\User::class, 'worksheet_reporters', 'worksheet_id')->withTrashed();
    }



    public function modul_list()
    {
        $modules = \App\Models\Modul::select('moduls.*')
            ->leftJoin('modul_appeals', 'modul_appeals.modul_id', 'moduls.id')
            ->where('modul_appeals.appeal_id', $this->appeal_id)
            ->get();

        $modules = $modules->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'count' => $item->wsm ? DB::table($item->wsm)->where('worksheet_id', $this->id)->count() : 0,
            ];
        });

        return $modules;
    }



    /**
     * Проверка пользователь участником РЛ
     */
    public function isExecutor($userId = null)
    {
        if (!$userId)
            $userId = auth()->user()->id;

        return $this->executors->contains('id', $userId) ? 1 : 0;
    }



    /**
     * Проверка на автора
     */
    public function isAuthor($userId = null)
    {
        if (!$userId)
            $userId = auth()->user()->id;

        return $this->author_id == $userId ? 1 : 0;
    }



    public function isLada()
    {
        if($this->company_id == 1)
            return 1;
        return 0;
    }



    public function isSaleDepartment()
    {
        if($this->structure_id == 1)
            return 1;
        return 0;
    }



    public function isSaleNewCar()
    {
        if($this->appeal_id == 1)
            return 1;
        return 0;
    }



    public function getCloseDateAttribute()
    {
        return $this->close_at ? $this->close_at->format('d.m.Y (H:i)') : '';
    }



    public function hasFiles() : bool
    {
        return ($this->files_count ?? 0) || ($this->trafic->files()->count() ? 1 : 0);
    }



    public function hasLinks() : bool
    {
        return ($this->links_count ?? 0) || ($this->trafic->links()->count() ? 1 : 0);
    }
}
