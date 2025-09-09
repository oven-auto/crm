<?php

namespace App\Services\Comment;

use App\Models\Discount;
use App\Models\Interfaces\CommentInterface;

Class DiscountComment extends AbstractComment
{
    private $type;
    private $amount;
    private $return;

    public function __construct(Discount $discount)
    {
        $this->type = $discount->type->name;
        $this->amount = $discount->sum->amount;
        $this->return = $discount->reparation->amount;
        
        if($discount->modulable_type == 'App\Models\WsmReserveNewCar')
            $this->data = [
                'author_id' => auth()->user()->id,
                'reserve_id' => $discount->modulable_id,
            ];
        else
            throw new \Exception('Не удается записать комментарий под скидку для '.$discount->modulable_type);
    }



    public function store(CommentInterface $obj)
    {
        return array_merge($this->data, [
            'text' => 'Заявлена новая скидка - '.$this->type.' ('.$this->amount.'/'.$this->return.').',
            'type' => 1,
        ]);
    }



    public function update(CommentInterface $obj)
    {
        return array_merge($this->data, [
            'text' => 'Скидка '.$this->type.' изменена.',
            'type' => 1,
        ]);
    }



    public function delete(CommentInterface $obj)
    {
        return array_merge($this->data, [
            'text' => 'Скидка '.$this->type.' аннулирована.',
            'type' => 1,
        ]);
    }
}