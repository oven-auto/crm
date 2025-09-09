<?php

namespace App\Classes\Telegram\Scenes;

use App\Classes\Telegram\AbstractScene;
use App\Classes\Telegram\SceneInterface;
use App\Classes\Telegram\Traits\ConstantToFunction;

Class MyeventScene extends AbstractScene implements SceneInterface
{
    use ConstantToFunction;

    public  $options = [
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ];

    public  $answer = [
        0 => 'Введите дату в формате ДД.ММ.ГГГГ, либо 0 для текущей даты.',
        1 => 'Список коммуникаций',
        'error' => "Ошибочка. Попробуй /help",
    ];

    public  $storageState = [
        1 => 'controll_date',
    ];

    public  $rules = [
        'controll_date' => 'date_or_current'
    ];

    private  $stateCount = 1;

    private $clientEventRepo;



    public function __construct($obj)
    {
        parent::__construct($obj);
        $this->clientEventRepo = new \App\Repositories\Client\ClientEventRepository();
    }



    public function bysinesLogic()
    {
        if(!$this->user)
        {
            $this->telegram->sendMessage($this->chatId, 'Вы не верифицировали аккаунт с СRM.', $this->options);
            return;
        }

        $events = $this->clientEventRepo->getEventsForTaskList([
            'executor_ids' => [$this->user->id],
            'control_date' => $this->getStorage('controll_date') ?? date('d.m.Y'),
            'show' => 'opening'
        ]);

        $this->setMessage($events);

    }



    public function setMessage($data, $str = '')
    {
        foreach($data as $item)
        {
            $val = [];
            $val[] = 'Событие №'.'<b>'.$item->id.'</b>'."\n";;
            $val[] = '<b>'.$item->status.'</b>'."\n";
            $val[] = $item->date_at->format('d.m.Y').' ('.$item->begin.') - '.$item->date_at->format('d.m.Y').' ('.$item->end.')'."\n";
            $val[] = $item->event->title;
            $str = join(' ', $val);
            $this->telegram->sendMessage($this->chatId, $str, $this->options);
        }
    }
}
