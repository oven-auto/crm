<?php 

namespace App\Services\Comment\Worksheet;

use App\Http\DTO\Worksheet\Service\CreateCommentServiceDTO;
use App\Models\SubAction;
use App\Models\SubActionComment;

class SubActionCommentService
{
    public static $statuses = [];

    private const VALUES = [
        'save',
    ];



    public function __construct(
        private SubAction $action, 
        private SubAction $old
    )
    {
        
    }



    public static function append(string $val)
    {
        if(in_array($val, self::VALUES))
            self::$statuses[] = $val;

        self::$statuses = array_unique(self::$statuses);
    }



    public static function handle(SubAction $action, SubAction $old)
    {
        $me = new self($action, $old);
        
        $me->dispatch();
    }



    public function dispatch()
    {
        $messages = [];
        
        foreach(self::$statuses as $item)
            $messages[] = match($item) {
                default => null,
            };
        
        foreach($messages as $item)
            if($item)
                SubActionComment::create((array)CreateCommentServiceDTO::fromArray([
                    'text' => $this->makeMessage($item),
                    'type' => 1,
                    'worksheet_id' => $this->action->worksheet_id,
                ]));
    }



    public function makeMessage(string $message)
    {
        $action = $this->action;
        
        $arr = [
            'id' => $action->id,
        ];

        foreach($arr as $key => $item)
            $message = str_replace($key, $item, $message);
        
        return $message;
    }



    public function save()
    {
        if($this->action->wasRecentlyCreated)
            self::append('create');
        else
            if($this->old->title != $this->action->title)
                self::append('update');
    }



    public function close()
    {
        if($this->old->closed_at != $this->action->closed_at && $this->action->closed_at)
            self::append('close');
    }
}