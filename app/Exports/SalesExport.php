<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Order::with('user')
            ->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->latest()
            ->get();
    }

    public function map($order): array
    {
        return [
            $order->invoice,
            $order->created_at->format('d M Y H:i'),
            $order->user->name ?? 'User Dihapus',
            ucfirst(str_replace('_', ' ', $order->status)),
            strtoupper($order->payment_method),
            $order->total,
        ];
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PENJUALAN'],
            ['Periode: ' . $this->startDate . ' s/d ' . $this->endDate],
            [''],
            [
                'No. Invoice',
                'Tanggal',
                'Pelanggan',
                'Status Pesanan',
                'Metode Pembayaran',
                'Total (Rp)',
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Title
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

        // Styling Table Headers
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        $sheet->getStyle('A4:F4')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A4:F4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A4:F4')->getFill()->getStartColor()->setARGB('FFE91E63'); // Beauty Store Pink Color

        // Format column F (Total) as Number
        $sheet->getStyle('F5:F'.$sheet->getHighestRow())
              ->getNumberFormat()
              ->setFormatCode('#,##0');

        return [
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }
}
