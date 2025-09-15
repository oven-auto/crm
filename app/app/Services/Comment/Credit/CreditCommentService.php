<?php

namespace App\Services\Comment\Credit;

use App\Helpers\String\StringHelper;
use App\Http\DTO\Worksheet\Service\CreateCommentServiceDTO;
use App\Models\WSMCredit;
use App\Models\WSMCreditComment;

Class CreditCommentService
{
    public static $statuses = [];

    private const VALUES = [
        'draft', 'status', 'close', 'contract', 'award_append', 'award_delete', 'sum', 'simple', 'return', 'delete'
    ];



    public function __construct(private WSMCredit $credit, private WSMCredit $old)
    {
        
    }



    public static function append(string $val)
    {
        if(in_array($val, self::VALUES))
            self::$statuses[] = $val;

        self::$statuses = array_unique(self::$statuses);
    }



    public static function handle(WSMCredit $credit, WSMCredit $old)
    {
        $me = new self($credit, $old);
        $me->status();
        $me->contract();
        $me->award();
        $me->sum();
        $me->simple();
        $me->close();
        $me->delete();
        $me->dispatch();
    }



    public function dispatch()
    {
        $messages = [];
        
        foreach(self::$statuses as $item)
            $messages[] = match($item) {
                'draft' => "Расчёт №id. Создан черновик кредитной завки (creditor, cost, firstpay, content, monthpay, author).",
                'status' => 'Расчёт №id. Кредитной заявке присвоен статус "status" (creditor, cost, firstpay, content, monthpay, author).',
                'close' => 'Расчёт №id. Кредит расторгнут. Удержано вознагрождение deduction (creditor, cost, firstpay, content, monthpay, author, register_at, decorator).',
                'contract' => 'Расчёт №id. Оформлен кредит (creditor, cost, firstpay, content, monthpay, author, register_at, decorator).',
                'award_append' => 'Расчёт №id. Начислено вознагрождение award_sum (creditor, cost, firstpay, content, monthpay, author, register_at, decorator).',
                'award_delete' => 'Расчёт №id. Начисленение вознагрождения award_sum аннулировано (creditor, cost, firstpay, content, monthpay, author, register_at, decorator).',
                'sum' => 'Расчёт №id. Расчётное вознагрождение award_sum (creditor, cost, firstpay, content, monthpay, author, register_at, decorator).',
                'simple' => 'Расчёт №id. Кредитная заявка упущена (creditor, cost, firstpay, content, monthpay, author).',
                'return' => 'Расчёт №id. Кредитная заявка возвращена в работу (creditor, cost, firstpay, content, monthpay, author).',
                'delete' => 'Расчёт №id. Черновик кредитной заявки удален (creditor, cost, firstpay, content, monthpay, author).',
                default => null,
            };
        
        foreach($messages as $item)
            if($item)
                WSMCreditComment::create((array)CreateCommentServiceDTO::fromArray([
                    'text' => $this->makeMessage($item),
                    'type' => 1,
                    'worksheet_id' => $this->credit->worksheet_id,
                ]));
    }



    public function makeMessage(string $message)
    {
        $credit = $this->credit;
        
        $arr = [
            'id' => $credit->id,
            'status' => $credit->status->name ?? 'Черновик',
            'creditor' => $credit->creditor->full_name ?? '',
            'cost' => 'ДКП '. StringHelper::moneyMask($credit->calculation->cost ?? 0),
            'firstpay' => 'ПВ '. StringHelper::moneyMask($credit->calculation->first_pay ?? 0),
            'content' => $credit->content->count() ? join(', ', $credit->content->map(function($item){
                return $item->name;
            })->toArray()) : 'БЕЗ ДП',
            'monthpay' => 'ЕП ' . StringHelper::moneyMask($credit->calculation->month_pay ?? 0),
            'author' => 'Автор заявки ' . ($credit->author ? $credit->author->cut_name : $credit->creator->cut_name),
            'register_at' => 'оформление ' . ($credit->contract ? $credit->contract->register_at->format('d.m.Y') : ''),
            'decorator' => 'ответственный за сделку ' . ($credit->contract ? $credit->contract->decorator->cut_name : ''),
            'deduction' => StringHelper::moneyMask($credit->deduction->sum ?? 0),
            'award_sum' => StringHelper::moneyMask($credit->award->sum ?? 0),
        ];

        foreach($arr as $key => $item)
            $message = str_replace($key, $item, $message);
        
        return $message;
    }



    public function status()
    {   
        if($this->credit->wasRecentlyCreated )
            self::append('draft');
        
        if($this->old->status_id !== $this->credit->status_id)
                self::append('status');
    }



    public function close()
    {
        if(!$this->old->close && $this->credit->close)
            self::append('close');
    }



    public function contract()
    {
        $old = $this->old->contract ? $this->old->contract->register_at : null;
        $new = $this->credit->contract ? $this->credit->contract->register_at : null;
      
        if($old != $new)
            self::append('contract');
    }



    public function award()
    {
        $old = $this->old->award->completed ?? null;
        $new = $this->credit->award->completed ?? null;

        if(!$old && $new)
            self::append('award_append');
        if($old && !$new)
            self::append('award_delete');
    }



    public function sum()
    {
        if(($this->old->award->sum ?? null) != ($this->credit->award->sum ?? null))
            self::append('sum');
    }



    public function simple()
    {
        $old = $this->old->calculation->simple ?? null;
        $new = $this->credit->calculation->simple ?? null;
        
        if(!$old && $new)
            self::append('simple');
        if($old && !$new)
            self::append('return');
    }



    public function delete()
    {
        if(!$this->credit->id)
        {
            $this->credit = $this->old;
            self::append('delete');
        }
    }
}