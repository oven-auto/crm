<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Данная модель является отображением сущности представления, НЕ ТАБЛИЦЫ
 * СОХРАНЕНИЕ/УДАЛЕНИЕ в нее происходит под капотом sql
 */
class CarStatusType extends Model
{
    use HasFactory;

    public const VALUES = [
        'free'      => 'free',
        'reserved'   => 'reserved',
        'client'    => 'client',
        'issued'    => 'issued',
        'saled'     => 'saled'
    ];



    public const STATES = [
        'free'          => 'Свободный',
        'reserved'      => 'Резерв',
        'client'        => 'Клиентский',
        'issued'        => 'Выдан',
        'saled'         => 'Продан'
    ];



    public const DATA = [
        'free'      => ['title'=>self::STATES['free'],      'color' => 0],
        'reserved'  => ['title'=>self::STATES['reserved'],  'color' => 2],
        'client'    => ['title'=>self::STATES['client'],    'color' => 1],
        'issued'    => ['title'=>self::STATES['issued'],    'color' => 5],
        'saled'     => ['title'=>self::STATES['saled'],     'color' => 4],
    ];



    public function get(string|int $suffix = '')
    {
        if(!isset(self::DATA[$this->status]))
            return self::DATA['free'];
        
        $res = self::DATA[$this->status];
        $res['title'].=' '.$suffix;   
        return $res;
    }
}
