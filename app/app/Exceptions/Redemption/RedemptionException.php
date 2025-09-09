<?php

namespace App\Exceptions\Redemption;

use Exception;

class RedemptionException extends Exception
{
    private const ERRORS = [
        'default'                       => 'Операция завершилась ошибкой.',
        'fact_purchase'                 => 'Фактический закуп не заполнен, не могу перенести такой автомобиль на склад.',
        'not_woking'                    => 'Эта оценка не является рабочей.',
        'without_vin'                   => 'Нельзя создать на складе автомобиль без VIN-номера.',
        'is_working'                    => 'Оценка этого автомобиля уже проводится в рамках данного рабочего листа.',
        'not_closing'                   => 'Оценка не завершена.',
    ];



    public function render()
    {
        return response()->json([
            "error" => true,
            "message" => $this->getError(),
            'success' => 0,
            'error' => implode(', ', [
                'Фаил где поймал исключение: '.$this->getFile(),
                'Cтрока с исключением: '.$this->getLine(),
            ])
        ], 404);
    }



    public function getError()
    {
        $key = $this->getMessage();

        if(array_key_exists($key, self::ERRORS))
            return self::ERRORS[$key];

        return self::ERRORS['default'];
    }
}
