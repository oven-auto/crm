<?php

namespace App\Classes\Telegram\Scenes;

use App\Classes\Telegram\AbstractScene;
use App\Classes\Telegram\SceneInterface;
use App\Classes\Telegram\Traits\ConstantToFunction;

Class MytraficScene extends AbstractScene implements SceneInterface
{
    use ConstantToFunction;

    public  $options = [
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ];

    public  $answer = [
        0 => 'Список трафика',
        'error' => "Ошибочка. Попробуй /help",
    ];

    private  $stateCount = 0;

    private $traficRepo;



    public function __construct($obj)
    {
        parent::__construct($obj);
        
        $this->traficRepo = new \App\Repositories\Trafic\TraficRepository();
    }



    public function bysinesLogic()
    {
        if(!$this->user)
        {
            $this->telegram->sendMessage($this->chatId, 'Вы не верифицировали аккаунт с СRM.', $this->options);
            return;
        }

        $trafics = $this->traficRepo->getTraficsForTaskList([
            'manager_id' => $this->user->id,
            'control_date' => date('d.m.Y'),
            'show' => 'opening'
        ]);

        $this->setMessage($trafics);

    }



    public function setMessage($trafics, $str = '')
    {
        foreach($trafics as $item)
        {
            $val = [];
            $val[] = 'Трафик №'.'<b>'.$item->id.'</b>';
            $val[] = '(от '.$item->created_at->format('d.m.Y').')'."\n";
            $val[] = '<b>'.$item->status->description.'</b>'."\n";
            $val[] = ($item->begin_at ? $item->begin_at->format('d.m.Y (H:i)') : 'Начало не указано')
                .' - '.($item->end_at ? $item->end_at->format('d.m.Y (H:i)') : 'Конец не указан');
            $str = join(' ', $val);

            $this->telegram->sendMessage($this->chatId, $str, $this->options);
        }
    }
}
