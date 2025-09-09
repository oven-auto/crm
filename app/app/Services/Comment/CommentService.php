<?php

namespace App\Services\Comment;

use App\Models\ClientEventLink;
use App\Models\Interfaces\CommentInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

Class CommentService
{
    public static function systemMessage(CommentInterface $model)
    {
            $arr['model'] = $model->selfRussianName();
            if(request()->isMethod('post'))
            {
                $arr['action'] = 'создан:';
                $arr['params'] = $model->changesList($model->toArray());
            }
            if(request()->isMethod('patch') && $model->getChanges())
            {
                $arr['action'] ='изменен:';
                $arr['params'] = $model->changesList($model->getChanges());
            }
            $message = join(' ', $arr);
            if(isset($arr['params']))
                $model->addComment($message);
    }

    public static function customMessage(CommentInterface $model, String $message)
    {
        $model->addComment($message);
    }
}
