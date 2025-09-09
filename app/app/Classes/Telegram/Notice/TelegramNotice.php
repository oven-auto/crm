<?php

namespace App\Classes\Telegram\Notice;

use App\Jobs\TelegramJob;
use Illuminate\Database\Eloquent\Model;
use App\Models\TelegramConnection;
use Illuminate\Support\Facades\Log;

Class TelegramNotice
{
    public $handler;

    private $telegram;

    public function __construct(Model $model = null)
    {
        if($model)
            $this->set($model);

        $this->telegram = \App\Classes\Telegram\Telegram::init();
    }



    private static function getNameSpace()
    {
        $reflection = new \ReflectionClass(self::class);
        return $reflection->getNamespaceName();
    }



    private static function factory($model)
    {
        $name = (new \ReflectionClass($model))->getShortName();

        $class = self::getNameSpace().'\\'.$name.'Notice';

        if(class_exists($class))
            return new $class($model);

        return false;
    }



    public function set(Model $model)
    {
        $this->handler = $this->factory($model);

        return $this;
    }



    public function __call($name,  $args)
    {
        if($this->handler && method_exists($this->handler, $name))
            $this->handler->$name($args);

        return $this;
    }



    public function __invoke()
    {
        return new $this();
    }



    public function send($arr)
    {
        $options = [
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];

        $arr[] = 47;

        $users = TelegramConnection::select('user_id')
            ->leftJoin('users', 'users.telegram_connection_id', 'telegram_connections.id')
            ->whereIn('users.id', $arr)
            ->pluck('user_id');
        
        foreach($users as $itemUser)
            TelegramJob::dispatch($itemUser, $this->handler->message, $options);
    }



    public static function push($user, $message, $options)
    {
        $telegram = \App\Classes\Telegram\Telegram::init();

        Log::channel('telegram')->info('Отправил сообщение: '.$message.'.');

        return $telegram->sendMessage($user, $message, $options);
    }



    public static function run(Model $model = null)
    {
        return new self($model);
    }
}
