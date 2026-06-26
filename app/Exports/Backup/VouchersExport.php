<?php

namespace App\Exports\Backup;

use App\Models\Voucher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VouchersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    public function collection()
    {
        return Voucher::all();
    }

    public function map($voucher): array
    {
        return [
            $voucher->id,
            $voucher->code,
            $voucher->discount_type,
            $voucher->discount_amount,
            $voucher->min_purchase,
            $voucher->valid_from,
            $voucher->valid_until,
            $voucher->quota,
            $voucher->used,
            $voucher->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Voucher',
            'Tipe Diskon',
            'Nominal/Persen Diskon',
            'Min. Belanja (Rp)',
            'Mulai Berlaku',
            'Batas Berlaku',
            'Kuota',
            'Telah Digunakan',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FF4CAF50'); // Green
        return [];
    }

    public function title(): string
    {
        return 'Data Voucher';
    }
}
