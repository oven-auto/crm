<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

Class CarExport implements FromView, ShouldAutoSize, WithStyles//FromCollection, WithCustomStartCell,,WithColumnWidths
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function setData($data)
    {
        $this->data = $data;
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
        return view('export.cars', [
            'trafics' => $this->data
        ]);
    }
}