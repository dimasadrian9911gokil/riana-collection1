<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $last24Hours = Carbon::now()->subHours(24);
        
        // 1. Kotak Metrik 24 Jam Terakhir
        $todaySales = Order::where('created_at', '>=', $last24Hours)
                           ->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])
                           ->sum('total') ?? 0;
                           
        $pendingOrdersToday = Order::where('created_at', '>=', $last24Hours)->where('status', 'menunggu_pembayaran')->count();
        $paidOrdersToday = Order::where('created_at', '>=', $last24Hours)->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])->count();
        $newUsersToday = User::where('created_at', '>=', $last24Hours)->count();

        // 2. Data Chart Penjualan 24 Jam Terakhir (Per 4 Jam)
        $chartData = ['labels' => [], 'data' => []];
        $currentTime = Carbon::now();
        
        // Buat 6 interval (masing-masing 4 jam), mulai dari 24 jam lalu sampai sekarang
        for ($i = 0; $i < 6; $i++) {
            $start = $currentTime->copy()->subHours(24 - ($i * 4));
            $end = $start->copy()->addHours(4);
            
            $total = Order::whereBetween('created_at', [$start, $end])
                          ->whereIn('status', ['sudah_dibayar', 'dikemas', 'dikirim', 'selesai'])
                          ->sum('total');
            
            $chartData['labels'][] = $start->format('H:i') . ' - ' . $end->format('H:i');
            $chartData['data'][] = (int) $total;
        }

        // 3. Produk Terlaris (Top 5 berdasarkan item yang paling banyak dibeli)
        $bestSellingProducts = \App\Models\OrderItem::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(qty) as total_sold'))
                                ->groupBy('product_id')
                                ->orderByDesc('total_sold')
                                ->take(5)
                                ->with('product')
                                ->get();

        // 4. Data Tambahan (Widget Baru)
        $lowStockProducts = Product::where('stock', '<=', 10)->orderBy('stock', 'asc')->take(5)->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        $totalCustomers = User::whereHas('roles', function($q) {
            $q->where('name', '!=', 'admin');
        })->orWhereDoesntHave('roles')->count();
        
        $totalProductsCount = Product::count();

        $orderStats = [
            'menunggu_pembayaran' => Order::where('status', 'menunggu_pembayaran')->count(),
            'menunggu_verifikasi' => Order::where('status', 'menunggu_verifikasi')->count(),
            'diproses' => Order::whereIn('status', ['sudah_dibayar', 'dikemas'])->count(),
            'dikirim' => Order::where('status', 'dikirim')->count(),
            'selesai' => Order::where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('status', 'dibatalkan')->count(),
        ];

        // 5. Brand Terlaris
        $bestSellingBrands = \App\Models\Brand::select('brands.id', 'brands.name', 'brands.logo')
            ->join('products', 'brands.id', '=', 'products.brand_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('SUM(order_items.qty) as total_sold')
            ->groupBy('brands.id', 'brands.name', 'brands.logo')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todaySales', 'pendingOrdersToday', 'paidOrdersToday', 'newUsersToday', 
            'bestSellingProducts', 'chartData',
            'lowStockProducts', 'recentOrders', 'totalCustomers', 'totalProductsCount',
            'bestSellingBrands', 'orderStats'
        ));
    }
}
