<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WeightExport implements FromView, WithHeadings, WithStyles
{
    protected $weights;

    public function __construct($weights)
    {
        $this->weights = $weights;
    }
    public function headings(): array       
    {
        return [
            'آی دی',
            'ولایت',
            'نام ترازو',
            'اسم مشتری / شرکت',
            'نوع منرال',
            'وزن خالص',
            'پول توزین',
            'محل تخلیه',
            'تعداد موتر',
            'تاریخ',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = $this->weights->count() + 1;
        $range = "A1:J{$rowCount}"; 
        return [
            
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'alignment' => ['horizontal' => 'center'],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1E3752']],
            ],
            
            $range => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
        ];
    }
  

    public function view(): View
    {
        return view('Exports.weight_export', [
            'weights' => $this->weights,
        ]);
    }
}
