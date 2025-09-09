<?php

namespace App\Repositories\Worksheet\CommentHistory;

use App\Models\Interfaces\IAmComment;

Class SubActionCommentData extends AbstractComment
{
    public $color;
    public $context;

    public const CONTEXT = 'Задача';

    public function __construct(IAmComment $model)
    {
        parent::__construct($model);
        $this->color = 'green';
        $this->context = self::CONTEXT.' №'.$model->sub_action_id;
    }
}
