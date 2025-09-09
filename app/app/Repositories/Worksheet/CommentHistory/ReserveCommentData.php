<?php

namespace App\Repositories\Worksheet\CommentHistory;

use App\Models\Interfaces\IAmComment;

Class ReserveCommentData extends AbstractComment
{
    public $color;
    public $context;

    public const CONTEXT = 'Резерв';

    public function __construct(IAmComment $model)
    {
        parent::__construct($model);
        $this->color = 'blue';
        $this->context = self::CONTEXT.' №'.$model->reserve_id;
    }
}