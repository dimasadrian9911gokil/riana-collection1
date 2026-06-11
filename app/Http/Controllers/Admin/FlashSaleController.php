<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::withCount('items')->latest()->get();
        return view('admin.flash_sales.index', compact('flashSales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'nullable|boolean'
        ]);

        FlashSale::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->has('is_active')
        ]);

        // Notifikasi ke Semua User (Simulasi Broadcast)
        $users = \App\Models\User::where('role', 'user')->get();
        \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\PromoNotification(
            'Flash Sale Baru!', 
            'Promo ' . $request->name . ' telah dimulai. Cek sekarang sebelum kehabisan!'
        ));

        return back()->with('success', 'Kampanye Flash Sale berhasil dibuat!');
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'nullable|boolean'
        ]);

        $flashSale->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Kampanye Flash Sale berhasil diperbarui!');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return back()->with('success', 'Kampanye Flash Sale beserta isinya berhasil dihapus!');
    }

    public function show(FlashSale $flashSale)
    {
        $flashSale->load('items.product');
        $products = Product::whereNotIn('id', $flashSale->items->pluck('product_id'))->get();
        return view('admin.flash_sales.show', compact('flashSale', 'products'));
    }

    public function addItem(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_price' => 'required|numeric|min:0',
            'stock_allocated' => 'required|integer|min:1'
        ]);

        FlashSaleItem::create([
            'flash_sale_id' => $flashSale->id,
            'product_id' => $request->product_id,
            'discount_price' => $request->discount_price,
            'stock_allocated' => $request->stock_allocated
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan ke Flash Sale!');
    }

    public function updateItem(Request $request, FlashSaleItem $item)
    {
        $request->validate([
            'discount_price' => 'required|numeric|min:0',
            'stock_allocated' => 'required|integer|min:1'
        ]);

        $item->update([
            'discount_price' => $request->discount_price,
            'stock_allocated' => $request->stock_allocated
        ]);

        return back()->with('success', 'Detail produk di Flash Sale berhasil diperbarui!');
    }

    public function removeItem(FlashSaleItem $item)
    {
        $item->delete();
        return back()->with('success', 'Produk berhasil dihapus dari Flash Sale!');
    }
}
