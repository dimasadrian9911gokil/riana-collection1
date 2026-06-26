<?php

namespace App\Exports\Backup;

use App\Models\FlashSaleItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FlashSalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    public function collection()
    {
        return FlashSaleItem::with(['flashSale', 'product'])->get();
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->flashSale->name ?? '-',
            $item->product->name ?? '-',
            $item->product->price ?? 0,
            $item->discount_price,
            $item->stock,
            $item->flashSale->start_time ?? '-',
            $item->flashSale->end_time ?? '-',
            ($item->flashSale && $item->flashSale->is_active) ? 'Aktif' : 'Nonaktif',
        ];
    }

    public function headings(): array
    {
        return [
            'ID Item',
            'Nama Event Flash Sale',
            'Produk',
            'Harga Asli (Rp)',
            'Harga Diskon (Rp)',
            'Stok Flash Sale',
            'Waktu Mulai',
            'Waktu Berakhir',
            'Status Event',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('FFD32F2F'); // Red
        
        $sheet->getStyle('D2:E'.$sheet->getHighestRow())
              ->getNumberFormat()
              ->setFormatCode('#,##0');
              
        return [];
    }

    public function title(): string
    {
        return 'Data Flash Sale';
    }
}
