<?php

namespace App\Services\Client;

use App\Helpers\Array\ArrayHelper;
use App\Models\ClientEventStatus;
use App\Classes\Telegram\Notice\TelegramNotice;
use App\Exceptions\Client\EventExcecutorAppendException;
use App\Models\User;
use App\Services\Comment\EventComment;
use App\Exceptions\Client\EventExcecutorDetachException;
use App\Exceptions\Client\EventReporterAttachException;
use App\Exceptions\Client\EventReporterIsAuthorException;
use App\Exceptions\Client\EventReporterNotException;

class EventExecutorReporterService
{
    /**
     * ДОБАВИТЬ ИСПОЛНИТЕЛЕЙ В СОБЫТИЕ
     * @param ClientEventStatus $event
     * @param array|int $users
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function append(ClientEventStatus $event, array|int|null $users = null): \Illuminate\Database\Eloquent\Collection
    {
        //проверка на пустых
        $users ?? throw new EventExcecutorAppendException();
        //если юзеры не массив, то принимаем юзерс за ИД
        $users = is_numeric($users) ? [$users] : $users;
        //Берем только уникальных
        $neededUserId = ArrayHelper::except($users, $event->executors->pluck('id')->toArray());
        //добавляем к исполнителям
        $event->executors()->attach($neededUserId);
        //шлем тг уведомлялку, хз самая бесполезная фича
        TelegramNotice::run($event)->executor()->send(ArrayHelper::except($neededUserId, $event->event->author_id));
        //берем всех актуальных исполнителей
        $executors = User::whereIn('id', $neededUserId)->get();
        //записываем неврот ебательски важный коммент
        EventComment::addUsers($event, $executors);

        return $executors;
    }



    /**
     * УДАЛИТЬ ИСПОЛНИТЕЛЕЙ В СОБЫТИЕ
     * @param ClientEventStatus $event
     * @param int $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function detach(ClientEventStatus $event, int $user): \Illuminate\Database\Eloquent\Collection
    {
        //Ошибка если пользователь автор
        $event->event->author_id != $user ?: throw new EventExcecutorDetachException();
        //Убрать из списка отчитавшихся
        $event->executors()->detach($user);
        //Получить пользователя
        $users = User::where('id', $user)->get();
        //Отправить коммент
        $users->each(function ($item) use ($event) {
            EventComment::delUser($event, $item);
        });

        return $users;
    }



    /**
     * ОТЧИТАТЬСЯ ЗА СОБЫТИЕ
     * @param ClientEventStatus $event
     * @param int $user
     * @return User
     */
    public function report(ClientEventStatus $event, int $userId): User
    {
        //Ошибка если уже отчитан
        !$event->reporters->contains('id', $userId) ?: throw new EventReporterAttachException();
        //Ошибка если автор
        $event->event->author_id != $userId ?: throw new EventReporterIsAuthorException();
        //Цепляем к отчитавшимся за событие
        $event->reporters()->attach($userId);
        //Получаем модель пользователя
        $user = User::find($userId);
        //Отправляем коммент
        EventComment::reportlUser($event, $user);
        //отцепляем от списка исполнителей
        $this->detach($event, $userId);
        //Отправляем уведомление автору события
        TelegramNotice::run($event)->report()->send([$event->event->author_id]);

        return $user;
    }



    /**
     * ОТМЕНА ОТЧЕТА ЗА СОБЫТИЕ
     * @param ClientEventStatus $event
     * @param int $user
     * @return User
     */
    public function deport(ClientEventStatus $event, int $userId): User
    {
        //Ошибка если нет в списке отчитавшихся
        $event->reporters->contains('id', $userId) ?: throw new EventReporterNotException();
        //Отцепляем от списка отчитавшихся
        $event->reporters()->detach($userId);
        //Получаем модель пользователя
        $user = User::findOrFail($userId);
        //Отправляем коммент
        EventComment::deportUser($event, $user);
        //Переносим в список  исполнителей
        $this->append($event, $userId);

        return $user;
    }
}
