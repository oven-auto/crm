<?php

namespace App\Exports;

use App\Models\Trafic;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TraficExport implements FromView, ShouldAutoSize, WithStyles//FromCollection, WithCustomStartCell,,WithColumnWidths
{
    private $data;

    public function __construct()
    {
        $this->data = collect();
    }

    public static function setData($data)
    {
        $export = new TraficExport();
        $export->data = $data;
        return $export;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension('1')->setRowHeight(40);
        $sheet->getStyle('1')
            ->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle('1')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension('2')->setRowHeight(35);
        $sheet->getStyle('2')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function view(): View
    {
        return view('export.trafic', [
            'trafics' => $this->data
        ]);
    }
}
