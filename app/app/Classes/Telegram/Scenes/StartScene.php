<?php

namespace App\Classes\Telegram\Scenes;

use App\Classes\Telegram\AbstractScene;
use App\Classes\Telegram\SceneInterface;
use App\Classes\Telegram\Traits\ConstantToFunction;

Class StartScene extends AbstractScene implements SceneInterface
{
    use ConstantToFunction;

    public $options = [
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ];

    private $answer = [
        0 => "<b>Привет я бот ОвенАвто[*].</b> \n\n".
            "Посмотреть список доступных команд /help \n",
        'error' => "Не флуди, а то забаню. Если хочешь спросить, спроси. Список команд можно узнать введя /help",
    ];

    public function bysinesLogic()
    {

    }
}
