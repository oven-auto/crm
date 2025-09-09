<?php

namespace App\Services\Client;

use App\Models\ClientEventStatus;
use App\Models\ClientEvent;
use App\Classes\Telegram\Notice\TelegramNotice;
use App\Exceptions\Client\EventCloseIsWorking;
use App\Exceptions\Client\EventCloseNotWhileIsNew;
use App\Helpers\Array\ArrayHelper;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Date\DateHelper;

Class EventCloseService
{
    private const NEW_EVENT = 'Назначена новая дата контроля ';
    private const INTERVAL_CLOSE = "Событие завершено";



    /**
     * ПРОВЕРКА ЯВЛЯЕТСЯ ЛИ СОБЫТИЕ ЦИКЛИЧНЫМ
     * @param ClientEvent $event
     * @return bool
     */
    private function isCycleEvent(ClientEvent $event) : bool
    {
        if($event->type->slug == 'once')
            return false;
        return true;
    }



    /**
     * ПОЛУЧИТЬ ДАТУ НАЧАЛА СОБЫТИЯ СЛЕДКЮЩЕЙ ИТЕРРАЦИИ
     * @param ClientEventStatus $eventStatus
     * @return String
     */
    private function rewrite(ClientEventStatus $eventStatus) : String
    {
        switch ($eventStatus->event->type->slug) {
            case 'everyyear': //если каждый год, то + 1 год
                $data = DateHelper::addYear($eventStatus->date_at);
                break;
            case 'everymonth'://если каждый месяц то +1 месяц
                $data = DateHelper::addMonth($eventStatus->date_at);
                break;
            case 'everyweek'://если каждую неделю то + 1 неделя
                $data = DateHelper::addWeek($eventStatus->date_at);
                break;
            case 'everyday'://если каждый день то +1 день
                $data = DateHelper::addDay($eventStatus->date_at);
                break;
            case 'quarterly'://если каждый квартал то + 3 месяца
                $data = DateHelper::addMonth($eventStatus->date_at, 3);
                break;
            case 'halfyear'://если каждые пол года то + 6 месяцев
                $data = DateHelper::addMonth($eventStatus->date_at, 6);
                break;
            default:
                $data = '';
                break;
        }

        return $data;
    }



    /**
     * ЗАПИСАТЬ В СТАТУС ОТМЕТКУ ОБ ЗАКРЫТИЕ СОБЫТИЕ
     * @param ClientEventStatus $eventStatus
     * @return void
     */
    private function writeStatus(ClientEventStatus $eventStatus) : void
    {
        //Ставим метку о закрытии текущей итерации события
        $eventStatus->fill([
            'confirm' => 'processed',
            'author_id' => auth()->user()->id,
            'processed_at' => now()->format('Y-m-d H:i:s'),
        ])->save();

        //Если событие цикличное
        if($this->isCycleEvent($eventStatus->event))
        {
            //дата новой итерации
            $dateAt = $this->rewrite($eventStatus);
            //время начала обработки
            $begin  = $eventStatus->begin_time ?? '09:00:00';
            //время конца обработки
            $end    = $eventStatus->end_time ?? '21:00:00';
            //создаем новую итеррацию
            $newStatus = $eventStatus->event->lastStatus()->create([
                'date_at' => $dateAt,
                'begin_time' => $begin,
                'end_time' => $end
            ]);

            $newStatus->executors()->attach($eventStatus->author_id);
            
            //Записываем новый коммент
            $this->writeComment($newStatus, self::NEW_EVENT.DateHelper::format($dateAt));
        }
    }



    /**
     * ЗАПИСАТЬ КОММЕНТАРИЙ
     * @param ClientEventStatus $eventStatus
     * @param String|null $comment
     * @return void
     */
    private function writeComment(ClientEventStatus $eventStatus, $comment = null) : void
    {
        if($comment)
            $eventStatus->lastComment()->create([
                'author_id'                     => auth()->user()->id,
                'text'                          => $comment,
                'event_id'                      => $eventStatus->event_id,
                'client_event_status_id'        => $eventStatus->id
            ]);
    }



    /**
     * ЗАКРЫТЬ СОБЫТИЕ
     * @param int $eventStatusId
     * @return ClientEventStatus
     */
    public function close(int $eventStatusId) : ClientEventStatus
    {
        //Получаем событие
        $eventStatus = ClientEventStatus::with('event')->find($eventStatusId);
        //Добавляем коммент о закрытии
        $this->writeComment($eventStatus, self::INTERVAL_CLOSE);
        //Закрываем событие
        $this->writeStatus($eventStatus);
        //Уведомление всем исполнителям события
        TelegramNotice::run($eventStatus)->close()->send(ArrayHelper::except($eventStatus->executors->pluck('id')->toArray(), Auth::id()));

        return $eventStatus;
    }



    public function resume(int $eventStatusId)
    {
        //Получаем событие
        $eventStatus = ClientEventStatus::with('event')->find($eventStatusId);
        //Ошибка если событие и так в работе
        !$eventStatus->isWork() ?: throw new EventCloseIsWorking();
        //Ошибка если есть следующая итерация которая в работе
        !$eventStatus->event->lastStatus->isWork() ?: throw new EventCloseNotWhileIsNew();
        //Очищаем старые данные на пустые
        $eventStatus->fill([
            'confirm' => 'waiting',
            'author_id' => NULL,
            'processed_at' => NULL
        ])->save();
        //оставляем коммент о возобновлении
        $eventStatus->lastComment()->create([
            'author_id' => auth()->user()->id,
            'text' => 'Событе вернулось в работу',
            'event_id' => $eventStatus->event_id,
            'client_event_status_id' => $eventStatus->id
        ]);

        return $eventStatus;
    }
}
