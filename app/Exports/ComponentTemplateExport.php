<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ComponentTemplateExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            [
                'part_no' => 'RM001',
                'part_name' => 'Raw Material Sample',
                'type' => 'rm',
                'unit_cost' => 50000,
                'unit' => 'kg',
            ],
            [
                'part_no' => 'LP001',
                'part_name' => 'Local Purchase Sample',
                'type' => 'lp',
                'unit_cost' => 15000,
                'unit' => 'pcs',
            ],
            [
                'part_no' => 'IP001',
                'part_name' => 'Import Purchase Sample',
                'type' => 'ip',
                'unit_cost' => 25000,
                'unit' => 'pcs',
            ],
            [
                'part_no' => 'CKD001',
                'part_name' => 'CKD Sample',
                'type' => 'ckd',
                'unit_cost' => 75000,
                'unit' => 'pcs',
            ],
        ]);
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            'part_no',
            'part_name',
            'type',
            'unit_cost',
            'unit',
        ];
    }

    /**
     * Define column formatting
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // unit_cost column
        ];
    }
}
