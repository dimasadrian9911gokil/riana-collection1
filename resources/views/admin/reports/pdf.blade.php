<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan ({{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }})</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        /* Header & Company Info */
        table.header-table { width: 100%; margin-bottom: 25px; border-bottom: 2px solid #E91E63; padding-bottom: 10px; }
        .header-table td { vertical-align: top; }
        .company-name { font-size: 24px; font-weight: bold; color: #E91E63; margin: 0 0 5px 0; }
        .company-address { font-size: 10px; color: #555; }
        
        .report-title-container { text-align: right; }
        .report-title { font-size: 20px; font-weight: bold; margin: 0 0 5px 0; letter-spacing: 1px; color: #222; text-transform: uppercase; }
        .report-date { font-size: 11px; color: #666; }

        /* Summary Section */
        .summary-box { 
            width: 100%; 
            margin-bottom: 20px; 
            border-collapse: collapse;
        }
        .summary-box td {
            background-color: #fff0f5;
            padding: 12px;
            border: 1px solid #ffccd5;
            text-align: center;
            width: 50%;
        }
        .summary-label { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; display: block; }
        .summary-value { font-size: 18px; font-weight: bold; color: #E91E63; margin: 0; }

        /* Main Data Table */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { 
            border: 1px solid #e0e0e0; 
            padding: 8px 6px; 
            text-align: left; 
            vertical-align: middle;
        }
        .data-table th { 
            background-color: #E91E63; 
            color: white; 
            font-size: 10px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            border-color: #d81b60;
        }
        .data-table tbody tr:nth-child(even) { background-color: #fcfcfc; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .text-danger { color: #d32f2f; }
        .text-success { color: #388e3c; }
        .text-muted { color: #777; font-size: 9px; }

        /* Footer */
        .footer { text-align: center; font-size: 9px; color: #999; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="50%">
                <h1 class="company-name">RIANA COLLECTION</h1>
                <div class="company-address">
                    Jl. Jendral Sudirman No. 123, Pekanbaru<br>
                    Riau, Indonesia 28111<br>
                    Telp: +62 812-3456-7890<br>
                    Email: admin@rianacollection.com
                </div>
            </td>
            <td width="50%" class="report-title-container">
                <h2 class="report-title">Laporan Penjualan</h2>
                <div class="report-date">
                    <strong>Periode:</strong><br>
                    {{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}
                </div><br>
                <div class="report-date">
                    <strong>Tanggal Cetak:</strong><br>
                    {{ date('d F Y, H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <table class="summary-box">
        <tr>
            <td>
                <span class="summary-label">Total Pesanan Selesai</span>
                <p class="summary-value">{{ $orders->count() }} Transaksi</p>
            </td>
            <td>
                <span class="summary-label">Total Pendapatan Bersih</span>
                <p class="summary-value">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Waktu & Invoice</th>
                <th width="20%">Pelanggan</th>
                <th class="text-center" width="10%">Status</th>
                <th class="text-right" width="12%">Subtotal</th>
                <th class="text-right" width="10%">Diskon</th>
                <th class="text-right" width="13%">Ongkir+Admin</th>
                <th class="text-right" width="15%">Total Bersih</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <div class="fw-bold">{{ $order->invoice }}</div>
                    <div class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </td>
                <td>
                    <div class="fw-bold">{{ $order->user->name ?? 'User Terhapus' }}</div>
                    <div class="text-muted">{{ $order->user->phone ?? '-' }}</div>
                </td>
                <td class="text-center">
                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                </td>
                <td class="text-right">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                <td class="text-right text-danger">
                    {{ $order->discount > 0 ? '-Rp'.number_format($order->discount, 0, ',', '.') : '-' }}
                </td>
                <td class="text-right text-muted">
                    Rp{{ number_format($order->shipping_cost + $order->admin_fee, 0, ',', '.') }}
                </td>
                <td class="text-right fw-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">Tidak ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Manajemen Riana Collection pada {{ date('d F Y, H:i') }}.
    </div>

</body>
</html>
