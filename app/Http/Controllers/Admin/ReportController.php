<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        
        $orders = Order::with('user')
            ->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();
            
        $totalRevenue = $orders->sum('total');

        $soldProducts = \App\Models\OrderItem::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(qty) as total_qty'), \Illuminate\Support\Facades\DB::raw('SUM(price * qty) as total_revenue'))
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])
                  ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->get();
            
        // Data Laporan Harian (Grafik Penjualan per Hari)
        $dailyChartData = ['labels' => [], 'data' => []];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $currentDate = clone $start;
        while ($currentDate <= $end) {
            $dateStr = $currentDate->format('Y-m-d');
            $totalDaily = Order::whereDate('created_at', $dateStr)
                ->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])
                ->sum('total') ?? 0;
                
            $dailyChartData['labels'][] = $currentDate->format('d M');
            $dailyChartData['data'][] = (int) $totalDaily;
            
            $currentDate->addDay();
        }

        // --- NEW ANALYTICS ---
        
        // 1. Total Produk Terjual
        $totalProductsSold = $soldProducts->sum('total_qty');
        
        // 2. Rata-rata Nilai Pesanan (AOV)
        $averageOrderValue = $orders->count() > 0 ? $totalRevenue / $orders->count() : 0;
        
        // 3. Pendapatan Berdasarkan Kategori (Untuk Pie Chart)
        $categoryChartData = ['labels' => [], 'data' => []];
        $categoryRevenue = [];
        foreach ($soldProducts as $item) {
            if ($item->product && $item->product->category) {
                $catName = $item->product->category->name;
                if(!isset($categoryRevenue[$catName])) $categoryRevenue[$catName] = 0;
                $categoryRevenue[$catName] += $item->total_revenue;
            }
        }
        foreach ($categoryRevenue as $name => $revenue) {
            $categoryChartData['labels'][] = $name;
            $categoryChartData['data'][] = $revenue;
        }
        
        // 4. Penggunaan Metode Pembayaran (Untuk Doughnut Chart)
        $paymentMethodChartData = ['labels' => [], 'data' => []];
        $paymentMethods = $orders->groupBy('payment_method')->map(function ($row) {
            return $row->count();
        });
        foreach ($paymentMethods as $method => $count) {
            $paymentMethodChartData['labels'][] = strtoupper($method);
            $paymentMethodChartData['data'][] = $count;
        }
        
        return view('admin.reports.index', compact(
            'orders', 'startDate', 'endDate', 'totalRevenue', 'soldProducts', 'dailyChartData',
            'totalProductsSold', 'averageOrderValue', 'categoryChartData', 'paymentMethodChartData'
        ));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        
        $orders = Order::with('user')
            ->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $fileName = 'laporan_penjualan_' . $startDate . '_sd_' . $endDate . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Tanggal', 'Invoice', 'Pelanggan', 'Status', 'Total (Rp)');

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Tanggal']  = $order->created_at->format('Y-m-d H:i');
                $row['Invoice']    = $order->invoice;
                $row['Pelanggan']  = $order->user->name ?? 'User Dihapus';
                $row['Status']  = $order->status;
                $row['Total (Rp)']  = $order->total;

                fputcsv($file, array($row['Tanggal'], $row['Invoice'], $row['Pelanggan'], $row['Status'], $row['Total (Rp)']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        
        $orders = Order::with('user')
            ->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();
            
        $totalRevenue = $orders->sum('total');

        $pdf = Pdf::loadView('admin.reports.pdf', compact('orders', 'startDate', 'endDate', 'totalRevenue'));
        
        return $pdf->download('laporan_penjualan_'.$startDate.'_sd_'.$endDate.'.pdf');
    }
}
