<?php

namespace App\Classes\Telegram\Scenes;

use App\Classes\Telegram\AbstractScene;
use App\Classes\Telegram\SceneInterface;
use App\Classes\Telegram\Traits\ConstantToFunction;

Class ErrorScene extends AbstractScene implements SceneInterface
{
    use ConstantToFunction;

    public $options = [
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ];

    public $answer = [
        0 => "<b>Не знаю как ответить.</b> \n\n".
            "Попробуй другую команду.\n".
            "Список команд /help",
        'error' => "Не флуди, а то забаню. Если хочешь спросить, спроси. Список команд можно узнать введя /help",
    ];

    public function bysinesLogic()
    {

    }
}
