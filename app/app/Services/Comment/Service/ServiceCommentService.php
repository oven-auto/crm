<?php

namespace App\Services\Comment\Service;

use App\Helpers\String\StringHelper;
use App\Http\DTO\Worksheet\Service\CreateCommentServiceDTO;
use App\Models\Worksheet\Service\WSMService;
use App\Models\WSMServiceComment;

Class ServiceCommentService
{
    public static $statuses = [];

    private const VALUES = [
        'status', 'award_append', 'award_delete', 'close', 'service', 'contract'
    ];



    public function __construct(
        private WSMService $service, 
        private WSMService $old
    )
    {
        
    }



    public static function append(string $val)
    {
        if(in_array($val, self::VALUES))
            self::$statuses[] = $val;

        self::$statuses = array_unique(self::$statuses);
    }



    public static function handle(WSMService $service, WSMService $old)
    {
        $me = new self($service, $old);
        $me->status();
        $me->contract();
        $me->award();
        $me->close();
        $me->dispatch();
    }



    public function dispatch()
    {
        $messages = [];
        
        foreach(self::$statuses as $item)
            $messages[] = match($item) {
                'status' => 'Расчёту №id в категории category присвоен статус "status" (name, cost, payment, award_proc)',
                'contract' => 'По расчёту №id в категории category зарегистрирован договор (name, cost, payment, award_proc, bank number, offer_at, register_at, decorator, manager)',
                'award_append' => 'По расчёту №id в категории category начислено вознагрождение award_sum (name, cost, payment, award_proc, bank number, offer_at, register_at, decorator, manager)',
                'award_delete' => 'По расчёту №id в категории category начисление вознагрождения award_sum аннулировано (name, cost, payment, award_proc, bank number, offer_at, register_at, decorator, manager)',
                'close' => 'Договор по расчёту №id в категории category расторгнут. Удержано вознагрождение deduction (name, cost, payment, award_proc, bank number, offer_at, register_at, decorator, manager)',
                default => null,
            };
        
        foreach($messages as $item)
            if($item)
                WSMServiceComment::create((array)CreateCommentServiceDTO::fromArray([
                    'text' => $this->makeMessage($item),
                    'type' => 1,
                    'worksheet_id' => $this->service->worksheet_id,
                ]));
    }



    public function makeMessage(string $message)
    {
        $service = $this->service;
        
        $arr = [
            'id' => $service->id,
            'status' => $service->state->state_name ?? '',
            'category' => $service->service->category->name ?? '',
            'name' => $service->service->name ?? '',
            'cost' => StringHelper::moneyMask($service->cost),
            'payment' => $service->payment->name ?? '',
            'award_proc' => 'КВ ' . $service->getProcentAward() . '%',
            'bank' => $service->provider->full_name ?? '',
            'number' => $service->contract ? '№'.$service->contract->number : '', 
            'offer_at' => 'оформлен '. ($service->contract ? $service->contract->begin_at->format('d.m.Y') : ''), 
            'register_at' => 'действует с ' . ($service->contract ? $service->contract->register_at->format('d.m.Y') : ''), 
            'decorator' => 'оформитель ' . ($service->contract ? $service->contract->decorator->cut_name : ''),
            'manager' => 'продавец '. ($service->contract ? $service->contract->manager->cut_name : ''),
            'deduction' => $service->deduction->sum ?? '',
            'award_sum' => $service->award ? StringHelper::moneyMask($service->award->sum) : '',
        ];

        foreach($arr as $key => $item)
            $message = str_replace($key, $item, $message);
        
        return $message;
    }



    public function contract()
    {
        $old = md5(serialize($this->old->contract ? $this->old->contract->toArray() : null));
        $new = md5(serialize($this->service->contract ? $this->service->contract->toArray() : null));

        if($old != $new)
            self::append('contract');
    }



    public function status()
    {
        $old = $this->old->state->state;
        $new = $this->service->state->state;

        if($old != $new)
            self::append('status');
    }



    public function award()
    {
        $old = $this->old->award->completed ?? null;
        $new = $this->service->award->completed ?? null;

        if(!$old && $new)
            self::append('award_append');
        if($old && !$new)
            self::append('award_delete');
    }



    public function close()
    {
        if(!$this->old->close && $this->service->close)
            self::append('close');
    }
}