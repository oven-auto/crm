<?php

namespace App\Classes\Notice;

class Notice
{
    private static $init;

    private static $messages = [];

    private function __construct() {}



    public static function getInstance()
    {
        if (is_null(self::$init)) 
            self::$init = new self();

        return self::$init;
    }



    public static function setMessage($message)
    {
        array_push(self::$messages, $message);
    }



    public static function getMessages()
    {
        return implode('<br>', self::$messages);
    }



    private function getActionString($action, $genus)
    {
        return match($genus){
            'male' => match($action){
                'store'     => 'создан',
                'update'    => 'изменен',
                'destroy'   => 'удален',
                'delete'    => 'удален',
                'close'     => 'закрыт', 
                'revert'    => 'востановлен',
                'restore'   => 'востановлен',
                default     => ''
            },
            'female' => match($action){
                'store'     => 'создана',
                'update'    => 'изменена',
                'destroy'   => 'удалена',
                'delete'    => 'удалена',
                'close'     => 'закрыта', 
                'revert'    => 'востановлена',
                'restore'   => 'востановлена',
                default     => ''
            },
            'neuter' => match($action){
                'store'     => 'создано',
                'update'    => 'изменено',
                'destroy'   => 'удалено',
                'delete'    => 'удалено',
                'close'     => 'закрыто', 
                'revert'    => 'востановлено',
                'restore'   => 'востановлено',
                default     => ''
            },
            'multi' => match($action){
                'store'     => 'созданы',
                'update'    => 'изменены',
                'destroy'   => 'удалены',
                'delete'    => 'удалены',
                'close'     => 'закрыты', 
                'revert'    => 'востановлены',
                'restore'   => 'востановлены',
                default     => ''
            },
            default => ''
        };
    }



    public static function make($subject, $action, $genus)
    {
        $obj = self::getInstance();
        $action = $obj->getActionString(action: $action, genus: $genus);
        $message = join(' ', [$subject, $action]).'.';
        return $message;
    }
}
