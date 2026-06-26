<?php

namespace App\Exports\Backup;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    public function collection()
    {
        return Category::withCount('products')->get();
    }

    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->slug,
            $category->products_count,
            $category->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kategori',
            'Slug',
            'Jumlah Produk',
            'Dibuat Pada',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FF00BCD4'); // Cyan
        return [];
    }

    public function title(): string
    {
        return 'Data Kategori';
    }
}
