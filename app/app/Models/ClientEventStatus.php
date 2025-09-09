<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Filterable;
use App\Models\Interfaces\CommentInterface;


class ClientEventStatus extends Model implements CommentInterface
{
    use HasFactory, Filterable;

    public $with = ['completer', 'description'];

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'processed_at' => 'datetime',
        'date_at' => 'datetime',
    ];



    public function attachInEvent(string $relation, $data)
    {
        $this->event->attachMethod($relation, $data);
    }



    public function writeComment(array $data)
    {
    }



    public function addComment(string $message)
    {
        $this->comments()->create([
            'author_id' => auth()->user()->id,
            'text' => $message,
            'event_id' => $this->event_id,
            'client_event_status_id' => $this->id
        ]);
    }



    public function selfRussianName()
    {
    }



    public function changesList($arr)
    {
    }



    public function getBeginAttribute()
    {
        return date('H:i', strtotime($this->begin_time));
    }



    public function getEndAttribute()
    {
        return date('H:i', strtotime($this->end_time));
    }



    public function getTimeStatusAttribute()
    {
        if ($this->begin_time == '09:00:00' && $this->end_time == '21:00:00')
            return 0;
        return 1;
    }





    public function scopeOnlyTableData($query)
    {
        return $query->select('client_event_statuses.*');
    }



    public function scopeListOrder($query)
    {
        return $query
            ->groupBy('client_event_statuses.id')
            ->orderBy('client_event_statuses.date_at', 'ASC')
            ->orderBy('client_event_statuses.begin_time')
            ->orderBy('client_event_statuses.confirm', 'ASC')
            ->orderBy('client_event_statuses.id', 'ASC');
    }



    public function scopeWithEventAndTrafic($query)
    {
        return $query->with(['event.files', 'trafic', 'trafics', 'reporters', 'completer'])
            ->with(['event' => function ($query) {
                $query->withCount('files')
                    ->withCount('links');
            }]);
    }



    public function getStatusAttribute()
    {
        return $this->description->name;
    }



    public function isWork()
    {
        if ($this->confirm == 'waiting')
            return 1;
        return 0;
    }



    public function completer()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }



    public function description()
    {
        return $this->hasOne(\App\Models\ClientEventStatusDescription::class, 'confirm', 'confirm')->withDefault();
    }



    public function event()
    {
        return $this->hasOne(\App\Models\ClientEvent::class, 'id', 'event_id')->withDefault()->with([
            'group', 'type', 'client', 'author',
        ]);
    }



    public function comments()
    {
        return $this->hasMany(\App\Models\ClientEventComment::class, 'client_event_status_id', 'id');
    }



    public function lastComment()
    {
        return $this->hasOne(\App\Models\ClientEventComment::class, 'client_event_status_id', 'id')->orderBy('id', 'DESC')->withDefault();
    }



    public function trafic()
    {
        return $this->hasOneThrough(
            \App\Models\Trafic::class,
            \App\Models\ClientEventTrafic::class,
            'event_status_id',
            'id',
            'id',
            'trafic_id'
        )->withDefault();
    }



    public function trafics()
    {
        return $this->hasManyThrough(
            \App\Models\Trafic::class,
            \App\Models\ClientEventTrafic::class,
            'event_status_id',
            'id',
            'id',
            'trafic_id'
        )->orderBy('id', 'DESC');
    }



    public function reporters()
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'client_event_reporters',
            'event_id',
            'user_id',
            'id'
        )->withPivot('created_at');
    }



    public function links()
    {
        return $this->hasMany(\App\Models\ClientEventLink::class, 'event_id', 'event_id');
    }



    public function files()
    {
        return $this->hasMany(\App\Models\ClientEventFile::class, 'client_event_status_id', 'id');
    }



    public function executors()
    {
        return $this->belongsToMany(\App\Models\User::class, 'client_event_status_executors', 'client_event_status_id', 'user_id', 'id')
            ->withTrashed();
    }
}
