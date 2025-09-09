<?php

namespace App\Classes\Telegram\Notice;

use \App\Models\ClientEventStatus;

Class SubActionNotice extends AbstractNotice
{
    public $message = '';

    /**
     * MESSAGE ON APPEND EXECUTOR
     */
    public function executor()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Задача №'.$this->model->id.' (РЛ №'.$this->model->worksheet_id).').';
        $arr[] = $this->model->title.'.';
        $arr[] = $user.': Добавляю Вас в качестве участника.';
        $arr[] = 'Срок '.$this->model->created_at->format('d.m.Y (H:i').'-'.$this->model->created_at->addMinutes($this->model->duration)->format('H:i)');
        $arr[] = self::italicStr('Теперь Вы отслеживаете эту задачу.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    /**
     * MESSAGE ON REPORT
     */
    public function report()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Задача №'.$this->model->id.' (РЛ №'.$this->model->worksheet_id).').';
        $arr[] = $this->model->title.'.';
        $arr[] = $user.': Я больше не работаю над этой задачей.';
        $arr[] = 'Отчитываюсь '.date('d.m.Y (H:i)');
        $arr[] = self::italicStr('Вы можете отменить отчет сотрудника по работе с этой задачей.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }



    /**
     * MESSAGE ON CLOSE
     */
    public function close()
    {
        $user = auth()->user() ? auth()->user()->cut_name : 'Система';

        $arr[] = self::boldStr('Задача №'.$this->model->id.' (РЛ №'.$this->model->worksheet_id).').';
        $arr[] = $this->model->title.'.';
        $arr[] = $user.': Задача не актуальна.';
        $arr[] = 'Отчитываюсь: '.date('d.m.Y (H:i)');
        $arr[] = self::italicStr('Вы больше не можете отслеживать эту задачу.');

        $resultStr = implode("\n", $arr);

        $this->message = $resultStr;
    }
}
