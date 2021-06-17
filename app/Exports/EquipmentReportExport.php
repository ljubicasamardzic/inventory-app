<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class EquipmentReportExport implements FromCollection, WithHeadings, ShouldAutosize, WithStyles, WithColumnWidths, WithStrictNullComparison
{
    public function __construct($data, $title) {
        $this->data = $data;
        $this->title = $title;
    }

    public function collection()  
    {
        return collect([$this->data]);
    }

    public function headings():array {
        return [
            [$this->title]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,            
        ];
    }

}
