<?php

namespace App\Exports\Backup;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    public function collection()
    {
        return Product::with(['category', 'brand'])->get();
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category->name ?? '-',
            $product->brand->name ?? '-',
            $product->price,
            $product->stock,
            $product->weight,
            $product->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Produk',
            'Kategori',
            'Brand',
            'Harga (Rp)',
            'Stok',
            'Berat (gram)',
            'Dibuat Pada',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FFE91E63'); // Pink
        
        $sheet->getStyle('E2:E'.$sheet->getHighestRow())
              ->getNumberFormat()
              ->setFormatCode('#,##0');
              
        return [];
    }

    public function title(): string
    {
        return 'Data Produk';
    }
}
