<?php

namespace App\Classes\Telegram;

abstract Class AbstractScene implements SceneInterface
{
    public $chatId;

    public $userId;

    public $telegram;

    public $text;

    protected $connection;

    protected $rule;

    protected $user;

    public abstract function options() : array;

    public abstract function answer() : array;

    public abstract function storageState() : array;

    public abstract function rules() : array;

    public abstract function stateCount() : int;

    public abstract function bysinesLogic();

    public function __construct(array $obj)
    {
        $this->chatId = $obj['chatId'];

        $this->userId = $obj['userId'];

        $this->text = $obj['text'];

        $this->telegram = \App\Classes\Telegram\Telegram::init();

        $this->connection = \App\Models\TelegramConnection::where('user_id', $this->userId)->first();

        $this->rule = new \App\Classes\Telegram\Rules($this);

        $this->user = $this->getUser();
    }



    public function prepareKeyboard(array $keyboard)
    {
        return json_encode([
            'inline_keyboard' => $keyboard,
        ]);
    }



    /**
     * VALID USER
     */
    public function getUser()
    {
        $user = \App\Models\User::where('telegram_connection_id', $this->connection->id)->first();

        if(!$user)
            return null;

        return $user;
    }



    public function getStorage($key = '')
    {
        $storage = $this->connection->storage;
        $storage = json_decode($storage, true);

        if($key)
            return $storage[$key];

        return $storage;
    }



    /**
     * DECRIMENT STATE CONNECTION
     */
    public function decrimentState()
    {
        $this->connection->state--;

        $this->connection->save();
    }



    /**
     * CHECK RULE FACTORY METHOD
     */
    public function check($val, $rules)
    {
        $rules = explode('|', $rules);

        $result = 1;

        foreach($rules as $itemRule)
        {
            $posNameDelimeter = strpos($itemRule, ':');

            if($posNameDelimeter != 0)
            {
                $ruleName = substr($itemRule, 0, $posNameDelimeter);

                $result = $this->rule->$ruleName($this->text, substr($itemRule, $posNameDelimeter + 1, strlen($itemRule) - $posNameDelimeter));
            }
            else
                $result = $this->rule->$itemRule($this->text);

            if($result == 0)
                return 0;
        }
        return 1;
    }



    public function sendCommand()
    {
        $check = 1;

        $currentState = $this->connection->state;

        if($currentState > $this->stateCount())
            $currentState = 'error';

        if(isset($this->storageState()[$currentState]))
        {
            $check = $this->check($this->text, $this->rules()[$this->storageState()[$currentState]]);

            if($check)
            {
                $arr = $this->connection->storage;
                $arr == null ? $arr = '' : $arr;
                $arr = json_decode($arr, true);
                $arr[$this->storageState()[$currentState]] = $this->text;
                $this->connection->storage = json_encode($arr);
                $this->connection->save();
            }
        }

        match ($check)
        {
            0 => $this->decrimentState(),
            default => $this->telegram->sendMessage($this->chatId, $this->answer()[$currentState], $this->options()),
        };

        if($this->stateCount() == $currentState)
        {
            $this->bysinesLogic();
        }
    }
}
