<?php

namespace App\Http\DTO\Client\ClientEvent;

use Illuminate\Support\Arr;

Class ClientEventTemplateDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $comment,
        public readonly string $group_id,
        public readonly string $type_id,
        public readonly string $title,
        public readonly string $executors,
        public readonly int $begin,
        public readonly int $author_id,
        public readonly bool $resolve,
        public readonly int|null $process_id,
        public readonly string|null $links,
    )
    {
        
    }



    public static function fromArray(array $arr)
    {
        $links = Arr::get($arr, 'links', null);

        return new self(
            name: Arr::get($arr, 'name', ''),
            comment: Arr::get($arr, 'comment', ''),
            group_id: Arr::get($arr, 'group', ''),
            type_id: Arr::get($arr, 'type', ''),
            title: Arr::get($arr, 'title', ''),
            executors: json_encode(Arr::get($arr, 'executors', [])),
            begin: Arr::get($arr, 'begin', 0),
            author_id: Arr::get($arr, 'author'),
            resolve: Arr::get($arr, 'resolve'),
            process_id: Arr::get($arr, 'process'),
            links: $links ? json_encode($links) : NULL,
        );
    }
}