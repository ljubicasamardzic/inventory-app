<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class OrderExport implements FromCollection, WithHeadings, ShouldAutosize, WithStyles, WithColumnWidths, WithStrictNullComparison
{
    public function __construct($data) {
        $this->data = $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]]
        ];
    }

    public function headings():array {
        return [
            "#",
            "Ticket_id",
            "Officer",
            "Equipment category",
            "Quantity", 
            "Price", 
            "Remarks", 
            "Deadline"
        ];
    }

    public function collection()
    {
        return collect([$this->data]);
    }

    public function columnWidths(): array
    {
        return [
            'G' => 60,            
        ];
    }
}
