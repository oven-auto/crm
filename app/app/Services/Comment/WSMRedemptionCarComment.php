<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\WSMRedemptionCar;
use App\Models\User;

Class WSMRedemptionCarComment extends AbstractComment
{
    public function __construct(WSMRedemptionCar $redemption)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'redemption_car_id' => $redemption->id,
        ];
    }



    public function create(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Заявлена новая оценка автомобиля',
            'type' => 1,
        ]);
    }



    public function offer(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Установлен новый фактический закуп',
            'type' => 1,
        ]);
    }



    public function purchase(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Установлен новое предложение клиенту',
            'type' => 1,
        ]);
    }



    public function calculation(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Установлен новая предворительная стоимость',
            'type' => 1,
        ]);
    }



    public function close(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Оценка завершена отказом',
            'type' => 1
        ]);
    }



    public function buy(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Оценка завершена выкупом',
            'type' => 1
        ]);
    }
}
