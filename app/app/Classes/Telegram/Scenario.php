<?php

namespace App\Classes\Telegram;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Objects\Update;

Class Scenario
{
    public $telegram;

    public function __construct()
    {
        $this->telegram = Telegram::init();
    }



    private static function getNameSpace()
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getNamespaceName();
    }



    public function handler()
    {
        $messages = $this->telegram->getUpdates();
        
        try {
            foreach($messages as $item)
            {
                $str = join(' ', [
                    'id'            => $item->message->from->id,
                    'firstname'     => $item->message->from->first_name,
                    'username'      => $item->message->from->username,
                    'text'          => '['.$item->message->text.']',
                ]);

                Log::channel('telegram')->info($str);
            }
        }
        catch(\Exception $e)
        {
            dump($e->getMessage());
        }
        
        foreach($messages as $item)
        {
            $callback   = @($item->callbackQuery->data);
            $text       = @($item->message->text);

            if(isset($text))
                $params = [
                    'text' => $text,
                    'chatId' => $item->message->chat->id,
                    'userId' => $item->message->from->id,
                ];
            elseif(isset($callback))
                $params = [
                    'text' => $text,
                    'chatId' => $item->callbackQuery->message->chat->id,
                    'userId' => $item->callbackQuery->from->id,
                ];

            $text = $callback ? $callback : $text;

            $scene = $this->sceneFactory($text, $params);

            if($scene)
                $scene->sendCommand();
        }
    }



    public function read(array $message)
    {
        $item = new Update($message);

        $callback   = @($item->callbackQuery->data);
        $text       = @($item->message->text);

        if(isset($text))
            $params = [
                'text' => $text,
                'chatId' => $item->message->chat->id,
                'userId' => $item->message->from->id,
            ];
        elseif(isset($callback))
            $params = [
                'text' => $text,
                'chatId' => $item->callbackQuery->message->chat->id,
                'userId' => $item->callbackQuery->from->id,
            ];

        $text = $callback ? $callback : $text;

        $scene = $this->sceneFactory($text, $params);

        if($scene)
            $scene->sendCommand();
    }



    private function sceneFactory(string $string, $params)
    {
        $isCommand = strpos($string, '/');

        if($isCommand === 0)
        {
            $className = ucfirst(trim($string, '/'));

            $class = self::getNameSpace().'\\Scenes\\'.$className.'Scene';

            if(class_exists($class))
            {
                $this->saveCommand($params, $string);
                return new $class($params);
            }
            else
            {
                $this->saveCommand($params, '/error');
                return self::errorScene($params);
            }
        }
        elseif($isCommand === false)
        {
            $prevAction = \App\Models\TelegramConnection::where('user_id', $params['userId'])->first();

            if($prevAction && $prevAction->last_command)
            {
                $className = ucfirst(trim($prevAction->last_command, '/'));

                $class = self::getNameSpace().'\\Scenes\\'.$className.'Scene';

                if(class_exists($class))
                {
                    $prevAction->state++;
                    $prevAction->save();
                    return new $class($params);
                }
            }
        }

        $this->saveCommand($params, '/error');
        return self::errorScene($params);
    }



    private static function errorScene($params)
    {
        return new \App\Classes\Telegram\Scenes\ErrorScene($params);
    }



    public function saveCommand($params, $command)
    {
        $obj = \App\Models\TelegramConnection::updateOrCreate(['user_id' => $params['userId']],[
            'chat_id' => $params['chatId'],
            'user_id' => $params['userId'],
            'last_command' => $command,
            'state' => 0,
            'storage' => null
        ]);
    }
}
