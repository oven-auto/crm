<?php

namespace App\Services\Worksheet;
use App\Models\User;
use App\Models\Worksheet;
use App\Models\WorksheetExecutor;
use Illuminate\Support\Collection;
use App\Services\Comment\Comment;
use App\Classes\Telegram\Notice\TelegramNotice;
use \App\Helpers\Array\ArrayHelper;

/**
 * Класс для добавления/удаления ответственных лиц из РЛ
 */
Class WorksheetExecutorReportService
{
    /**
    * Добавить ответственных лиц в РЛ
    * @param Worksheet $worksheet РЛ в который хотим добавить ответственных лицо
    * @param Collection $users Пользователи которых хотим сделать ответственными лицами
    */
    public function attach(Worksheet|int $worksheet, Collection|array|int $users, $comment = true) : void
    {
        $worksheet = is_numeric($worksheet) ? Worksheet::findOrfail($worksheet) : $worksheet;
        //Если просто число, то переводим в массив
        $users = is_numeric($users) ? [$users] : $users;
        //Если коллекция то переводим в массив
        $users = ($users instanceof Collection) ? $users->pluck('id')->toArray() : $users;
        //Добавляем автора в массив
        $users[] = $worksheet->author_id;
        //Отбрасываем не уникальных
        $users = ArrayHelper::except($users, $worksheet->executors->pluck('id')->toArray());
        //Присоединяем как исполнителей
        $worksheet->executors()->attach($users);
        //Комментируем
        !$comment ?: Comment::add(self::commentModel($worksheet, new User()), 'attach',  User::whereIn('id',$users)->get());
        //Отправляем уведомление
        TelegramNotice::run($worksheet)->executor()->send(ArrayHelper::except($users, auth()->user()->id));
    }

    /**
    * Удалить ответственного из РЛ
    * @param Worksheet $worksheet РЛ в который хотим удаить ответственное лицо
    * @param User $users Пользователь которого хотим удалить
    */
    public function detach(Worksheet|int $worksheet, User|int $user) : void
    {
        $worksheet = is_numeric($worksheet) ? Worksheet::findOrfail($worksheet) : $worksheet;
        $user = is_numeric($user) ? $user : $user->id;
        //detach user from executor list
        $worksheet->executors()->detach($user);
    }



    /**
     * ОТЧИТАТЬСЯ ЗА РЛ
     * @param int|Worksheet $worksheet
     * @param int $user
     * @return void
     */
    public function report(int|Worksheet $worksheet) : void
    {
        $userId = auth()->user()->id;

        if(is_numeric($worksheet))
            $worksheet = Worksheet::findOrFail($worksheet);

        if($userId === $worksheet->author_id)
            throw new \Exception('Вы автор рабочего листа, и не имеете возможности отчитываться');

        if($worksheet->reporters->contains('id', $userId))
            throw new \Exception('Вы уже находитесь в списке отчитавшихся этого рабочего листа');

        if(!$worksheet->executors->contains('id', $userId))
            throw new \Exception('Вы не можете отчитаться за рабочий лист, тк не являетесь его участником');

        $worksheet->reporters()->attach($userId);

        TelegramNotice::run($worksheet)->report()->send([$worksheet->author_id]);

        Comment::add(self::commentModel($worksheet, auth()->user()), 'report');

        $this->detach($worksheet, $userId);
    }



    /**
     * СНЯТЬ ОТЧЕТ ПОЛЬЗОВАТЕЛЯ С РАБОЧЕГО ЛИСТА
     * @param int|Worksheet $worksheet
     * @param int|User $user
     * @return void
     */
    public function deport(int|Worksheet $worksheet, int|User $user) : void
    {
        $worksheet = is_numeric($worksheet) ? Worksheet::findOrFail($worksheet) : $worksheet;

        $user = is_numeric($user) ? User::findOrFail($user) : $user;

        $worksheet->reporters->contains('id', $user->id) ?: throw new \Exception('Пользователя нет в списке отчитавшихся');
        //delete user from reporter list
        $worksheet->reporters()->detach($user->id);
        //update reporters
        $worksheet->load('reporters');
        //make comment
        Comment::add(self::commentModel($worksheet, $user), 'deport');
        //append user to executor list
        $this->attach($worksheet, $user->id, false);
    }



    private static function commentModel($worksheet, $user) : WorksheetExecutor
    {
        $executor = new WorksheetExecutor();

        $executor->fill(['worksheet_id' => $worksheet->id, 'user_id' => $user->id]);

        return $executor;
    }
}
