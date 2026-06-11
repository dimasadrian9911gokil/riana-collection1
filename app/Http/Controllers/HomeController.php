<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan data dinamis.
     */
    public function index(): View
    {
        // 1. Ambil semua kategori untuk menu "Kategori Populer"
        $categories = Category::all();

        // 2. Ambil 8 produk terbaru untuk bagian "Produk Terbaru"
        $latestProducts = Product::latest()->take(8)->get();

        // 3. Ambil Kampanye Flash Sale yang sedang aktif
        $activeFlashSale = \App\Models\FlashSale::where('is_active', true)
                                ->where('start_time', '<=', now())
                                ->where('end_time', '>=', now())
                                ->latest()
                                ->first();

        $flashSaleItems = $activeFlashSale ? $activeFlashSale->items()->with('product')->take(4)->get() : collect();

        return view('home', compact('categories', 'latestProducts', 'activeFlashSale', 'flashSaleItems'));
    }

    /**
     * Menampilkan halaman khusus Flash Sale.
     */
    public function flashsale(): View
    {
        $activeFlashSale = \App\Models\FlashSale::where('is_active', true)
                                ->where('start_time', '<=', now())
                                ->where('end_time', '>=', now())
                                ->latest()
                                ->first();

        $flashSaleItems = $activeFlashSale 
            ? \App\Models\FlashSaleItem::where('flash_sale_id', $activeFlashSale->id)->with('product')->paginate(12)
            : \App\Models\FlashSaleItem::where('id', -1)->paginate(12);

        return view('flashsale', compact('activeFlashSale', 'flashSaleItems'));
    }

    /**
     * Menampilkan daftar semua Brand (Database).
     */
    public function brands(): View
    {
        $brands = \App\Models\Brand::orderBy('name')->get();
        return view('brands', compact('brands'));
    }

    /**
     * Menampilkan halaman kategori dinamis.
     */
    public function categories(): View
    {
        // Mengambil semua kategori dari database beserta jumlah produknya
        $categories = Category::withCount('products')->get();

        return view('categories', compact('categories'));
    }
}