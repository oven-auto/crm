<?php

namespace App\Classes\Telegram\Notice;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractNotice
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public static function boldStr($str)
    {
        return '<b>'.$str.'</b>';
    }

    public function italicStr($str)
    {
        return '<i>'.$str.'</i>';
    }

    public function crossStr($str)
    {
        return '<s>'.$str.'</s>';
    }
}
