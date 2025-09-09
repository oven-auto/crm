<?php

namespace App\Exceptions\Client;

use Exception;

class EventCloseNotWhileIsNew extends Exception
{
    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => 'У этого событя есть новая не завершенная итерация, вся история хранится в ней.',
            'success' => 0,
            'error' => implode(', ', [
                'Фаил где поймал исключение: '.$this->getFile(),
                'Cтрока с исключением: '.$this->getLine(),
            ])
        ], 404);
    }
}
