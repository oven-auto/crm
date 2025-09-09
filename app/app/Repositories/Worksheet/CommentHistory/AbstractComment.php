<?php

namespace App\Repositories\Worksheet\CommentHistory;

use App\Models\Interfaces\IAmComment;

Class AbstractComment
{
    public $created_at;
    public $text;
    public $writer;
    public $status;
    public $type;
    public $author_id;
    public $id;

    public function __construct(IAmComment $model)
    {
        $vars = get_object_vars($this);

        $data = $model->getCommentData();

        foreach($data as $key => $item)
            if(array_key_exists($key, $vars))
                $this->$key = $item;
    }
}
