<?php

namespace App\Http\DTO\Worksheet\Service;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

Class CreateCommentServiceDTO
{
    public function __construct(
        public readonly int $author_id,
        public readonly string $text,
        public readonly int $worksheet_id,
        public readonly int $type
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            author_id: Arr::get($data, 'author_id', Auth::id()),
            text: Arr::get($data, 'text'),
            worksheet_id: Arr::get($data, 'worksheet_id'),
            type: Arr::get($data, 'type', 0),
        );
    }
}