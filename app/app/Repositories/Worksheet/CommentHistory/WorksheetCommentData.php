<?php

namespace App\Repositories\Worksheet\CommentHistory;

use App\Models\Interfaces\IAmComment;

Class WorksheetCommentData extends AbstractComment
{
    public $color;
    public $context;

    public function __construct(IAmComment $model)
    {
        parent::__construct($model);
        $this->color = '';
        $this->context = '';
    }
}
