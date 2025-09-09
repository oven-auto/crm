<?php

namespace App\Services\Car;

use App\Models\Car;
use Carbon\Carbon;

Class CalculatePaidDate
{
    public static function handler(Car $car)
    {
        $invoiceDate = $car->getInvoiceDate();

        if(!$invoiceDate)
            return;

        $paidBeginCount = $car->delivery_terms->term->begin_period;

        $paidEndCount = $car->delivery_terms->term->end_period;
       
        $datePaid       = Carbon::createFromFormat('d.m.Y', $invoiceDate)->addDays($paidBeginCount);
        
        $dateControl    = Carbon::createFromFormat('d.m.Y', $invoiceDate)->addDays($paidEndCount);
        
        $car->savePaidDate($datePaid);

        $car->saveControlPaidDate($dateControl);
    }
}