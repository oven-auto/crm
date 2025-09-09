<?php

namespace App\Classes\Telegram\Notice;

use \App\Models\ClientEventStatus;

Class ClientEventStatusNotice extends AbstractNotice
{
    public $message = '';

    public function executor()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Коммуникация №'.$this->model->id).'.';
        $arr[] = 'Клиент: '.$this->model->event->client->full_name;
        $arr[] = 'Тема: '.$this->model->event->title.'.';
        $arr[] = $user.': Добавляю Вас в качестве участника.';
        $arr[] = 'Категория: '.$this->model->event->group->name;
        $arr[] = 'Начало '.$this->model->date_at->format('d.m.Y').' ('.$this->model->begin.')';
        $arr[] = self::italicStr('Теперь Вы отслеживаете это событие.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    public function report()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Коммуникация №'.$this->model->id).'.';
        $arr[] = 'Клиент: '.$this->model->event->client->full_name;
        $arr[] = 'Тема: '.$this->model->event->title.'.';
        $arr[] = $user.': Я больше не работаю с этим событием.';
        $arr[] = 'Отчитываюсь '.date('d.m.Y (H:i)');
        $arr[] = self::italicStr('Вы можете отменить отчет сотрудника по работе с этим событием.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    public function close()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Коммуникация №'.$this->model->id).'.';
        $arr[] = 'Клиент: '.$this->model->event->client->full_name;
        $arr[] = 'Тема: '.$this->model->event->title.'.';
        $arr[] = $user.': Завершаю работу с этим событием.';
        $arr[] = 'Завершено '.date('d.m.Y (H:i)');
        $arr[] = self::italicStr('Вы больше не можете отслеживать работу с этим событием.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    public function comment()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Коммуникация №'.$this->model->id).'.';
        $arr[] = 'Клиент: '.$this->model->event->client->full_name;
        $arr[] = 'Тема: '.$this->model->event->title.'.';
        $arr[] = $user.': Добавил комментарий';
        $arr[] = $this->model->lastComment->text;
        $arr[] = self::italicStr('Вы больше не можете отслеживать работу с этим событием.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }
}
