<?php

namespace App\Models\Traits;

Trait GetCommentData
{
    public function getCommentData() : array
    {
        return [
            'id'            => $this->id,
            'created_at'    => $this->created_at,
            'text'          => $this->text,
            'writer'        => $this->author->cut_name,
            'status'        => $this->status,
            'type'          => $this->type,
            'author_id'     => $this->author_id,
        ];
    }
}
