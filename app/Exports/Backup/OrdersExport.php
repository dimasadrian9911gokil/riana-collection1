<?php

namespace App\Exports\Backup;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    public function collection()
    {
        return Order::with('user')->get();
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->invoice,
            $order->user->name ?? 'User Dihapus',
            $order->user->email ?? '-',
            ucfirst(str_replace('_', ' ', $order->status)),
            strtoupper($order->payment_method),
            $order->total,
            $order->resi ?? '-',
            $order->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'No. Invoice',
            'Nama Pelanggan',
            'Email Pelanggan',
            'Status Pesanan',
            'Metode Pembayaran',
            'Total Pembayaran (Rp)',
            'Resi Pengiriman',
            'Waktu Pesanan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('FF000000');
        
        $sheet->getStyle('G2:G'.$sheet->getHighestRow())
              ->getNumberFormat()
              ->setFormatCode('#,##0');
              
        return [];
    }

    public function title(): string
    {
        return 'Data Pesanan (Orders)';
    }
}
