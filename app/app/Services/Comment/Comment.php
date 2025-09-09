<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;

Class Comment
{
    public static $comment;

    private static function makeFactory($model)
    {
        $name = (new \ReflectionClass($model))->getShortName();

        $class = self::getNameSpace().'\\'.$name.'Comment';

        if(class_exists($class))
            self::$comment = new $class($model);
    }



    private static function getNameSpace()
    {
        $reflection = new \ReflectionClass(self::class);
        
        return $reflection->getNamespaceName();
    }



    public static function add(CommentInterface $model, string $action, $data = [])
    {
        self::makeFactory($model);

        if(method_exists(self::$comment, $action))
        {
            $param[] = $model;
            if($data)
                $param[] = $data;

            return $model->writeComment(
                call_user_func_array(array(self::$comment, $action), $param)
            );
        }
    }



    public static function text(CommentInterface $model, string $message)
    {
        self::makeFactory($model);

        return $model->writeComment(self::$comment->text($message));
    }



    public static function getComment()
    {
        return self::$comment->getData();
    }
}
