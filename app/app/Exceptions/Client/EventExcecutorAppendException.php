<?php

namespace App\Exceptions\Client;

use Exception;

class EventExcecutorAppendException extends Exception
{
    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => 'Не могу добавить участника, так как не обнаружен его индентификатор.',
            'success' => 0,
            'error' => implode(', ', [
                'Фаил где поймал исключение: '.$this->getFile(),
                'Cтрока с исключением: '.$this->getLine(),
            ])
        ], 404);
    }
}
