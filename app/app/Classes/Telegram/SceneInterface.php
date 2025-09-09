<?php

namespace App\Classes\Telegram;

interface SceneInterface
{
    public function sendCommand();
    public function getStorage($key = '');
}
