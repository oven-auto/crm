<?php

namespace App\Services\Comment;

use App\Models\Interfaces\CommentInterface;
use App\Models\WsmReserveNewCarContract;

Class WsmReserveNewCarContractComment extends AbstractComment
{
    public function __construct(WsmReserveNewCarContract $contract)
    {
        $this->data = [
            'author_id' => auth()->user()->id,
            'reserve_id' => $contract->reserve_id,
        ];
    }



    public function store(CommentInterface $contract)
    {
        $text = 'Зарегистрирован первичный контракт от ';

        if($contract->PdkpOfferDate)
            $text .= $contract->PdkpOfferDate.' (ПДКП с поставкой '.$contract->PdkpDeliveryDate.').';
        if($contract->DkpOfferDate)
            $text .= $contract->DkpOfferDate.' (ДКП).';

        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }



    public function update(WsmReserveNewCarContract $contract)
    {
        $arr = $contract->getChanges();

        if(array_key_exists('dkp_closed_at', $arr) && $arr['dkp_closed_at'])
            return $this->delete($contract);

        $text = "В карточку договора внесены изменения.";
        
        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }



    public function delete(CommentInterface $contract)
    {
        $text = "Договор расторгнут от ".$contract->DkpCloseDate.'.';
        
        return array_merge($this->data, [
            'text' => $text,
            'type' => 1,
        ]);
    }
}