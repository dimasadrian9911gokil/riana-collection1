<?php

namespace App\Exports\Backup;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BrandsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    public function collection()
    {
        return Brand::all();
    }

    public function map($brand): array
    {
        return [
            $brand->id,
            $brand->name,
            $brand->slug,
            $brand->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Brand',
            'Slug',
            'Dibuat Pada',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:D1')->getFill()->getStartColor()->setARGB('FFFF9800'); // Orange
        return [];
    }

    public function title(): string
    {
        return 'Data Brand';
    }
}
