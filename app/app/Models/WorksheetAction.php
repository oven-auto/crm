<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\CommentInterface;

class WorksheetAction extends Model implements CommentInterface
{
    use HasFactory;

    private const CONTROL_OVERDUE = 1;
    private const CONTROL_ACTUAL = 2;
    private const CONTROL_COMMING = 3;

    public function writeComment(array $data)
    {
        return WorksheetActionComment::create($data);
    }

    // public function addComment(string $message)
    // {

    // }

    // public function changesList()
    // {

    // }

    // public function selfRussianName()
    // {

    // }

    public $casts = [
        'begin_at'      => 'datetime', 
        'end_at'        => 'datetime', 
        'created_at'    => 'datetime', 
        'updated_at'    => 'datetime'
    ];

    protected $guarded = [];

    public function getActualStatus()
    {
        return "Актуальное событие: {$this->task->name} {$this->begin_at->format('d.m.Y (H:i)')}";
    }
    public function getBeginDateAttribute()
    {
        if($this->begin_at)
            return $this->begin_at->format('d.m.Y (H:i)');
        return '';
    }

    public function getEndDateAttribute()
    {
        if($this->end_at)
            return $this->end_at->format('d.m.Y (H:i)');
        return '0';
    }

    public function task()
    {
        return $this->hasOne(\App\Models\Task::class,'id','task_id')->withDefault();
    }

    public function isWorking()
    {
        return $this->status == 'work' ? true : false;
    }

    public function isWaiting()
    {
        return $this->end_at > now() ? true : false;
    }

    public function last_comment()
    {
        return $this->hasOne(\App\Models\WorksheetActionComment::class,'action_id', 'id')->orderBy('id','DESC')->withDefault();
    }

    public function last_user_comment()
    {
        return $this->hasOne(\App\Models\WorksheetActionComment::class,'action_id', 'id')->where('type', 0)->orderBy('id','DESC')->withDefault();
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\WorksheetActionComment::class,'action_id', 'id')->with('author');
    }

    public function author()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'author_id')->withDefault()->withTrashed();
    }

    public function statusMsg()
    {
        switch ($this->status){
            case 'work':
                return 'Ожидает';
            case 'confirm':
                return 'Подтверждено';
            case 'abort':
                return 'Отменено';
            default:
                return '';
        }
    }

    public static function getStatuses()
    {
        return [

                ['id' => 'work', 'name' => 'В работе'],
                ['id' => 'confirm',  'name' => 'Подтверждено'],
                ['id' => 'abort',  'name' => 'Отменено']

        ];
    }

    public function worksheet()
    {
        return $this->hasOne(\App\Models\Worksheet::class, 'id', 'worksheet_id')->withDefault();
    }

    public function getControlStatus()
    {
        $currentTime = date('Y-m-d H:i');
        if($currentTime > $this->end_at)
            return self::CONTROL_OVERDUE;
        if($currentTime >= $this->begin_at && $currentTime <= $this->end_at)
            return self::CONTROL_ACTUAL;
        if($currentTime < $this->begin_at)
            return self::CONTROL_COMMING;
    }

    public function beginDate()
    {
        $status = $this->getControlStatus();
        switch ($status){
            case 1:
                return $this->begin_at->format('d.m.Y (H:i)').'-'.$this->end_at->format('d.m.Y (H:i)');
            case 2:
                return $this->begin_at->format('d.m.Y (H:i)').'-'.$this->end_at->format('d.m.Y (H:i)');
            case 3:
                $diff = $this->begin_at->diff(now());
                $arr = [
                    $diff->h.'ч.',
                    $diff->i.'мин.',
                ];
                return 'Начало через '.join(' ',$arr);
        }
    }

    public function dateFillColor()
    {
        $status = $this->getControlStatus();
        switch ($status){
            case 1:
                return 'red';
            case 2:
                return 'green';
            case 3:
                return 'yellow';
        }
    }
}
