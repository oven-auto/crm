<?php

namespace App\Http\DTO\Client\ClientEvent;

use Illuminate\Support\Arr;

Class ClientEventCreateDTO
{
    public function __construct(
        public readonly int $client_id,
        public readonly string $date_at,
        public readonly string $title,
        public readonly int $group_id,
        public readonly int $type_id,
        public readonly string|null $text,
        public readonly array $executors,
        public readonly string|null $begin_time,
        public readonly string|null $end_time,
        public readonly int $personal,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            client_id:      Arr::get($data, 'client_id'),
            date_at:        Arr::get($data, 'date_at'),
            title:          Arr::get($data, 'title'),
            group_id:       Arr::get($data, 'group_id'),
            type_id:        Arr::get($data, 'type_id'),
            text:           Arr::get($data, 'text'),
            executors:      Arr::get($data, 'executors', []),
            begin_time:     Arr::get($data, 'begin_time') ?? '09:00',
            end_time:       Arr::get($data, 'end_time') ?? '21:00',
            personal:       Arr::get($data, 'personal', 0)
        );
    }



    public function getEventData()
    {
        return Arr::only((array) $this, ['client_id', 'title', 'group_id', 'type_id', 'personal']);
    }



    public function getStatusData()
    {
        return Arr::only((array) $this, ['date_at', 'begin_time', 'end_time']);
    }
}