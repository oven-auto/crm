<?php

namespace App\Exceptions\Client;

use Exception;

class EventCloseIsWorking extends Exception
{
    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => 'Это событие в работе, его не нужно возвращать.',
            'success' => 0,
            'error' => implode(', ', [
                'Фаил где поймал исключение: '.$this->getFile(),
                'Cтрока с исключением: '.$this->getLine(),
            ])
        ], 404);
    }
}
