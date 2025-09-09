<?php

namespace App\Repositories\Worksheet\Modules\Reserve;

use App\Models\WsmReserveComment;

Class ReserveCommentRepository
{
    public function getWithOutSystem(array $data)
    {
        $query = WsmReserveComment::query();

        if(isset($data['reserve_id']))
            $query->where('reserve_id', $data['reserve_id']);

        $query->where('type', 0);

        $comments = $query->get();

        return $comments;
    }



    public function create(array $data)
    {
        $comment = WsmReserveComment::create(array_merge(
            $data,
            ['author_id' => auth()->user()->id],
        ));

        return $comment;
    }
}