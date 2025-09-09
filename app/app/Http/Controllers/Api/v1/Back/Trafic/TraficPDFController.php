<?php

namespace App\Http\Controllers\Api\v1\Back\Trafic;

use App\Http\Controllers\Controller;
use App\Models\Trafic;
use Barryvdh\DomPDF\Facade\Pdf;

class TraficPDFController extends Controller
{
    public function __invoke(Trafic $trafic)
    {
        $pdf = Pdf::loadView('pdf.trafic', [
            'trafic' =>$trafic
        ]);

        return $pdf->stream('trafic.pdf', ['Attachment' => 0]);
    }
}
