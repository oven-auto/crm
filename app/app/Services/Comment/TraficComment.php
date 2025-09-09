<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\Trafic;
use Illuminate\Support\Facades\Auth;

Class TraficComment extends AbstractComment
{
    public function __construct(Trafic $model)
    {   
        $this->data = [
            'author_id' => Auth::id(),
            'trafic_id' => $model->id,
        ];
    }

    public function show(Trafic $model)
    {
        return array_merge($this->data, [
            'text' => 'Трафик просматривался.'
        ]);
    }

    public function create(Trafic $model)
    {
        return array_merge($this->data, [
            'text' => 'Трафик создан.'
        ]);
    }

    public function update(Trafic $model)
    {
        return array_merge($this->data, [
            'text' => 'Трафик изменен.'
        ]);
    }

    public function delete(Trafic $model)
    {
        return array_merge($this->data, [
            'text' => 'Трафик удален.'
        ]);
    }

    public function close(Trafic $model)
    {
        return array_merge($this->data, [
            'text' => 'Трафик упущен.'
        ]);
    }


}
