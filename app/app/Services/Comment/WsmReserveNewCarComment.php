<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\WsmReserveNewCar;

Class WsmReserveNewCarComment extends AbstractComment
{
    public function __construct(WsmReserveNewCar $reserve)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'reserve_id' => $reserve->id,
        ];
    }



    public function store(CommentInterface $model)
    {
        $vin = $model->car->vin; 
        $vin = $vin ? '('.$vin.')' : '';

        return array_merge($this->data, [
            'text' => 'Зарегистрирован резерв на новый автомобиль '.$vin.'.',
            'type' => 1,
        ]);
    }



    public function delete(CommentInterface $model)
    {
        $vin = $model->car->vin; 
        $vin = $vin ? '('.$vin.')' : '';

        return array_merge($this->data, [
            'text' => 'Резерв на новый автомобиль аннулирован '.$vin.'.',
            'type' => 1,
        ]);
    }   
}