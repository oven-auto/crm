<?php

namespace App\Models;

use App\Models\Interfaces\CommentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sabberworm\CSS\Comment\Commentable;

class ClientUnion extends Model implements CommentInterface
{
    use HasFactory;

    protected $guarded = [];

    public function writeComment(array $data)
    {
        ClientComment::create($data);
    }

    public static function fillData($clientId, $unionClientId)
    {
        $union = new self();
        $union->parent = $clientId;
        $union->client_id = $unionClientId;
        return $union;
    }
}
