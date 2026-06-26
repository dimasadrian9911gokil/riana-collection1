<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, Cart, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;

class OrderController extends Controller
{
    // Store: Memproses checkout, mengurangi stok, dan menghapus keranjang jika bukan Beli Sekarang
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['courier' => 'required|string', 'payment_method' => 'required|string']);
        $user = Auth::user();
        
        $alamatUtama = $user->addresses()->where('is_default', 1)->first();
        if (!$alamatUtama) {
            return back()->with('error', 'Silakan atur alamat pengiriman utama Anda terlebih dahulu di profil.');
        }

        $isBuyNow = $request->has('buy_now_product_id');

        if ($isBuyNow) {
            $product = Product::findOrFail($request->buy_now_product_id);
            $cartItem = new Cart();
            $cartItem->product_id = $product->id;
            $cartItem->qty = $request->buy_now_qty;
            $cartItem->variant = $request->buy_now_variant ?? 'Standard';
            $cartItem->setRelation('product', $product);
            $cartItems = collect([$cartItem]);
        } else {
            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
            if ($cartItems->isEmpty()) return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        try {
            $order = DB::transaction(function () use ($cartItems, $request, $user, $isBuyNow, $alamatUtama) {
                $subtotal = $cartItems->sum(fn($item) => $item->product->final_price * $item->qty);
                $shipping = 0;
                if ($request->courier !== 'Pickup') {
                    $shippingArea = \App\Models\ShippingArea::where('name', $request->courier)->where('is_active', true)->first();
                    $shipping = $shippingArea ? $shippingArea->cost : 20000;
                }
                $adminFee = 0;
                
                $discount = 0;
                if ($request->has('voucher_id') && !empty($request->voucher_id)) {
                    $voucher = \App\Models\Voucher::find($request->voucher_id);
                    $hasUsedVoucher = \App\Models\Order::where('user_id', $user->id)->where('voucher_id', $voucher->id)->exists();
                    if ($voucher && $voucher->is_active && $subtotal >= $voucher->min_spend && !$hasUsedVoucher) {
                        if ($voucher->discount_type == 'percentage') {
                            $discount = $subtotal * ($voucher->discount_amount / 100);
                        } else {
                            $discount = $voucher->discount_amount;
                        }
                    }
                }
                
                $total = ($subtotal + $shipping + $adminFee) - $discount;

                $order = Order::create([
                    'user_id' => $user->id,
                    'recipient_name' => $alamatUtama->recipient_name,
                    'recipient_phone' => $alamatUtama->phone,
                    'shipping_address' => $alamatUtama->address . ($alamatUtama->district ? ', Kec. ' . $alamatUtama->district : '') . ($alamatUtama->postal_code ? ' ' . $alamatUtama->postal_code : ''),
                    'city' => $alamatUtama->city,
                    'province' => $alamatUtama->province,
                    'invoice' => 'INV-' . date('YmdHis') . '-' . $user->id,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shipping,
                    'admin_fee' => $adminFee,
                    'discount' => $discount,
                    'total' => $total,
                    'courier' => $request->courier,
                    'payment_method' => $request->payment_method,
                    'status' => 'menunggu_pembayaran',
                    'voucher_id' => $discount > 0 && isset($voucher) ? $voucher->id : null
                ]);

                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    $finalPrice = $product->final_price;
                    $activeFlashSaleItem = $product->getActiveFlashSaleItem();
                    
                    $maxQty = $activeFlashSaleItem ? min($product->stock, $activeFlashSaleItem->stock_allocated) : $product->stock;
                    if ($maxQty < $item->qty) throw new Exception("Stok atau kuota {$product->name} tidak cukup.");

                    OrderItem::create([
                        'order_id' => $order->id, 
                        'product_id' => $item->product_id, 
                        'product_name' => $product->name, 
                        'price' => $finalPrice, 
                        'qty' => $item->qty, 
                        'subtotal' => $finalPrice * $item->qty,
                        'variant' => $item->variant ?? 'Standard'
                    ]);
                    
                    $product->decrement('stock', $item->qty);
                    if ($activeFlashSaleItem) {
                        $activeFlashSaleItem->decrement('stock_allocated', $item->qty);
                    }
                }
                
                // Hapus keranjang hanya jika transaksi normal (bukan Beli Sekarang)
                if (!$isBuyNow) {
                    Cart::where('user_id', $user->id)->delete();
                }

                return $order;
            });

            // Notifikasi ke User dan Admin
            $user->notify(new \App\Notifications\SystemNotification(
                'Pesanan Berhasil Dibuat',
                "Pesanan Anda ({$order->invoice}) berhasil dicatat.",
                'fa-shopping-bag',
                'text-success',
                route('orders.show', $order->id)
            ));

            $admins = \App\Models\User::role('admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\SystemNotification(
                'Pesanan Baru Masuk',
                "{$user->name} baru saja membuat pesanan ({$order->invoice}).",
                'fa-box',
                'text-primary',
                url('/admin/orders')
            ));

            return redirect()->route('orders.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    // Fungsi konfirmasi pembayaran manual oleh user dengan upload bukti
    public function confirmPayment(\Illuminate\Http\Request $request, $id): RedirectResponse
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        // Cek kedaluwarsa sebelum diproses
        if ($order->checkExpiry()) {
            return back()->with('error', 'Pesanan sudah kedaluwarsa karena melewati batas 30 menit.');
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update([
            'status' => 'menunggu_verifikasi',
            'payment_proof' => $path
        ]);

        // Kirim notifikasi ke Admin
        $admins = \App\Models\User::role('admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\SystemNotification(
            'Verifikasi Pembayaran',
            "Pengguna {$order->user->name} telah mengunggah bukti pembayaran untuk pesanan {$order->invoice}.",
            'fa-receipt',
            'text-info',
            url('/admin/orders/' . $order->id)
        ));

        return redirect()->route('orders.show', $order->id)->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function changePaymentMethod(\Illuminate\Http\Request $request, $id): RedirectResponse
    {
        $request->validate([
            'payment_method' => 'required|in:BCA,BNI,Mandiri,DANA,QRIS'
        ]);

        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Metode pembayaran tidak dapat diubah karena pesanan sudah diproses.');
        }

        if ($order->checkExpiry()) {
            return back()->with('error', 'Pesanan sudah kedaluwarsa.');
        }

        $order->update(['payment_method' => $request->payment_method]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Metode pembayaran berhasil diubah.');
    }

    public function cancel($id): RedirectResponse
    {
        $order = Order::with('items')->where('user_id', Auth::id())->findOrFail($id);

        if (!in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'dibatalkan']);
            
            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->qty);
                    $activeFlashSaleItem = $product->getActiveFlashSaleItem();
                    if ($activeFlashSaleItem) {
                        $activeFlashSaleItem->increment('stock_allocated', $item->qty);
                    }
                }
            }
        });

        return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // Endpoint untuk fitur Realtime Polling
    public function getStatusJson(Order $order)
    {
        // Pastikan hanya pemilik pesanan atau admin yang bisa mengaksesnya
        if (Auth::id() !== $order->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->checkExpiry();

        return response()->json([
            'status' => $order->status,
            'status_label' => ucwords(str_replace('_', ' ', $order->status)),
            'tracking_number' => $order->tracking_number,
            'courier' => strtoupper($order->courier ?? 'Kurir')
        ]);
    }

    // Index: Menampilkan daftar pesanan dengan statistik status yang akurat
    public function index(): View 
    { 
        $userId = Auth::id();
        
        $orders = Order::where('user_id', $userId)->latest()->get();
        foreach ($orders as $order) {
            $order->checkExpiry();
        }

        $counts = [
            'menunggu_pembayaran' => Order::where('user_id', $userId)->where('status', 'menunggu_pembayaran')->count(),
            'sudah_dibayar'       => Order::where('user_id', $userId)->where('status', 'sudah_dibayar')->count(),
            'dikemas'             => Order::where('user_id', $userId)->where('status', 'dikemas')->count(),
            'dikirim'             => Order::where('user_id', $userId)->where('status', 'dikirim')->count(),
            'selesai'             => Order::where('user_id', $userId)->where('status', 'selesai')->count(),
        ];

        return view('orders.index', compact('orders', 'counts')); 
    }

    public function history(): View { 
        $orders = Order::with('items.product')->where('user_id', Auth::id())->latest()->get();
        foreach ($orders as $order) { $order->checkExpiry(); }
        return view('orders.history', ['orders' => $orders]); 
    }
    
    public function show($id): View { 
        $order = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($id);
        $order->checkExpiry();
        return view('orders.show', ['order' => $order]); 
    }
    
    public function pay($id): View|RedirectResponse 
    { 
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $order->checkExpiry();
        
        // Jika tidak ada snap_token atau metode pembayaran COD, alihkan
        if (!$order->snap_token || $order->payment_method === 'COD') {
            return redirect()->route('orders.show', $order->id);
        }

        return view('orders.pay', compact('order')); 
    }
    
    public function success($id): View 
    { 
        $order = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($id);
        return view('orders.success', compact('order')); 
    }
}