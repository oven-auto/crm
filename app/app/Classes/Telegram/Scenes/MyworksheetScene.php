<?php

namespace App\Classes\Telegram\Scenes;

use App\Classes\Telegram\AbstractScene;
use App\Classes\Telegram\SceneInterface;
use App\Classes\Telegram\Traits\ConstantToFunction;

Class MyworksheetScene extends AbstractScene implements SceneInterface
{
    use ConstantToFunction;

    public  $options = [
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ];

    public  $answer = [
        0 => 'Введите дату в формате ДД.ММ.ГГГГ, либо 0 для текущей даты.',
        1 => 'Список рабочих листов',
        'error' => "Ошибочка. Попробуй /help",
    ];

    public  $storageState = [
        1 => 'controll_date',
    ];

    public  $rules = [
        'controll_date' => 'date_or_current'
    ];

    private  $stateCount = 1;

    private $worksheetRepo;



    public function __construct($obj)
    {
        parent::__construct($obj);
        $this->worksheetRepo = new \App\Repositories\Worksheet\WorksheetRepository();
    }



    public function bysinesLogic()
    {
        if(!$this->user)
        {
            $this->telegram->sendMessage($this->chatId, 'Вы не верифицировали аккаунт с СRM.', $this->options);
            return;
        }

        $worksheets = $this->worksheetRepo->getAmountList([
            'executor_ids' => [$this->user->id],
            'control_date' => $this->getStorage('controll_date') ?? date('d.m.Y'),
            'show' => 'opening'
        ]);
        //dd($worksheets);
        $this->setMessage($worksheets);

    }



    public function setMessage($trafics, $str = '')
    {
        foreach($trafics as $item)
        {
            $val = [];
            $val[] = 'Рабочий лист №'.'<b>'.$item->id.'</b>'."\n";
            $val[] = ($item->type?'Действие - '.$item->type:'Связанная задача №'.$item->sub_action_id)."\n";
            $val[] = '<b>Статус</b> '.$item->status."\n";
            $val[] = $item->begin_at . ' - '. $item->end_at;
            $str = join(' ', $val);

            $this->telegram->sendMessage($this->chatId, $str, $this->options);
        }
    }
}
