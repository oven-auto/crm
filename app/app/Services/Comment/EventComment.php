<?php

namespace App\Services\Comment;

use App\Models\ClientEventLink;
use App\Models\Interfaces\CommentInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

Class EventComment extends CommentService
{
    public static function addUsers(CommentInterface $model, Collection $users)
    {
        if($users->count())
            self::customMessage($model, 'Добавлен новый участник события '. implode(', ', $users->map(function($item){
                return $item->cut_name;
            })->toArray()));
    }

    public static function delUser(CommentInterface $model, User $user)
    {
        self::customMessage($model, 'Удален участник события '.$user->cut_name);
    }

    public static function reportlUser(CommentInterface $model, User $user)
    {
        self::customMessage($model, 'Сотрудник отчитался и больше не является участником события');
    }

    public static function deportUser(CommentInterface $model, User $user)
    {
        self::customMessage($model, 'Отчет об исполнении сотрудника '.$user->cut_name.' отозван');
    }

    public static function appendFile(CommentInterface $model)
    {
        self::customMessage($model, 'Добавлены файлы');
    }

    public static function deleteFile(CommentInterface $model)
    {
        self::customMessage($model, 'Удалены файлы');
    }

    public static function appendLink(CommentInterface $model, ClientEventLink $link)
    {
        self::customMessage($model, 'Добавлена ссылка');
    }

    public static function deleteLink(CommentInterface $model, ClientEventLink $link)
    {
        self::customMessage($model, 'Удалена ссылка ');
    }
}
