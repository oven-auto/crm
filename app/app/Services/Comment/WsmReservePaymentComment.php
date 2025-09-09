<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\WsmReservePayment;

Class WsmReservePaymentComment extends AbstractComment
{
    private $date;
    private $amount;
    private $type;



    public function __construct(WsmReservePayment $pay)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'reserve_id' => $pay->reserve_id,
        ];

        $this->date = now()->format('d.m.Y');
        $this->amount = number_format($pay->amount, 0, '', ' ').'р';
        $this->type = $pay->payment->name;
    }



    public function store(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Регистрация оплаты за автомобиль от '.$this->date.' ('.$this->amount.' '.$this->type.').',
            'type' => 1,
        ]);
    }



    public function update(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Редактирование оплаты за автомобиль от '.$this->date.' ('.$this->amount.' '.$this->type.').',
            'type' => 1,
        ]);
    }



    public function delete(CommentInterface $model)
    {
        return array_merge($this->data, [
            'text' => 'Оплата за автомобиль от '.$this->date.' ('.$this->amount.' '.$this->type.') аннулирована.',
            'type' => 1,
        ]);
    }
}