<?php

namespace App\Repositories\Worksheet\CommentHistory;

use App\Models\Interfaces\IAmComment;

Class RedemptionCommentData extends AbstractComment
{
    public $color;
    public $context;

    public const CONTEXT = 'Оценка';

    public function __construct(IAmComment $model)
    {
        parent::__construct($model);
        $this->color = 'red';
        $this->context = self::CONTEXT.' №'.$model->redemption_car_id;
    }
}
