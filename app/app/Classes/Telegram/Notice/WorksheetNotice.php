<?php

namespace App\Classes\Telegram\Notice;

use \App\Models\ClientEventStatus;

Class WorksheetNotice extends AbstractNotice
{
    public $message = '';



    /**
     * MESSAGE ON APPEND EXECUTOR
     */
    public function executor()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Рабочий лист №'.$this->model->id).'.';
        $arr[] = 'Клиент: '.$this->model->client->full_name;
        $arr[] = $user.': Добавляю Вас в качестве участника.';
        $arr[] = $this->model->appeal->name.' ('.$this->model->company->name.').';
        $arr[] = self::italicStr('Теперь Вы отслеживаете работу с этим клиентом.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    /**
     * MESSAGE ON CHANGE CLIENT ACTION
     */
    public function action(array $params = [])
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $this->model->load('last_action');

        if($this->model->status_id == 'work')
        {
            $task[] = $this->model->last_action->task->name;
            $task[] = $this->model->last_action->begin_at->format('d.m.Y');
            $task[] = '('.$this->model->last_action->begin_at->format('H:i').'-'.$this->model->last_action->end_at->format('H:i').')';
            $task = implode(' ', $task);

            $arr[] = self::boldStr('Рабочий лист №'.$this->model->id).'.';
            $arr[] = 'Клиент: '.$this->model->client->full_name;
            $arr[] = $user.': Планирую работу с клиентом.';
            $arr[] = self::crossStr(array_shift($params));
            $arr[] = $task;
            $arr[] = self::italicStr('Если Вы не хотите отслеживать работу с этим клиентом, отчитайтесь по РЛ.');
        }
        else
        {
            $task[] = $this->model->last_action->task->name;
            $task[] = $this->model->last_action->begin_at->format('d.m.Y');
            $task[] = '('.$this->model->last_action->begin_at->format('H:i').')';
            $task = implode(' ', $task);

            $arr[] = self::boldStr('Рабочий лист №'.$this->model->id).'.';
            $arr[] = 'Клиент: '.$this->model->client->full_name;
            $arr[] = $user.': Завершаю работу с клиентом.';
            $arr[] = $task;
            $arr[] = self::italicStr('Вы больше не можете отслеживать работу с этим клиентом.');
        }

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    /**
     * MESSAGE ON REPORT
     */
    public function report()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Рабочий лист №'.$this->model->id).'.';
        $arr[] = 'Клиент: '.$this->model->client->full_name;
        $arr[] = $user.': Я больше не работаю с этим клиентом.';
        $arr[] = 'Отчитываюсь '.date('d.m.Y (H:i)');
        $arr[] = self::italicStr('Вы можете отменить отчет сотрудника по работе с этим клиентом.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }
}
