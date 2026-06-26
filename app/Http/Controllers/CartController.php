<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Tampilkan semua produk di keranjang user.
     */
    public function index(): View
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
            
        // Hitung subtotal dengan memastikan relasi product tidak null (gunakan final_price)
        $subtotal = $cartItems->sum(function($item) {
            return $item->product ? ($item->product->final_price * $item->qty) : 0;
        });
        
        // Cari voucher terbaik (potongan terbesar) yang BELUM PERNAH DIPAKAI
        $vouchers = \App\Models\Voucher::where('is_active', true)
                        ->where('min_spend', '<=', $subtotal)
                        ->whereNotIn('id', function($query) {
                            $query->select('voucher_id')
                                  ->from('orders')
                                  ->where('user_id', Auth::id())
                                  ->whereNotNull('voucher_id');
                        })
                        ->get();
        
        $bestVoucher = null;
        $maxDiscountValue = 0;

        foreach ($vouchers as $voucher) {
            $discountValue = 0;
            if ($voucher->discount_type == 'percentage') {
                $discountValue = $subtotal * ($voucher->discount_amount / 100);
            } else {
                $discountValue = $voucher->discount_amount;
            }

            if ($discountValue > $maxDiscountValue) {
                $maxDiscountValue = $discountValue;
                $bestVoucher = $voucher;
            }
        }
        
        $discount = $maxDiscountValue;
            
        return view('cart.index', compact('cartItems', 'subtotal', 'bestVoucher', 'discount'));
    }

    /**
     * Tambah produk ke keranjang dengan validasi stok yang ketat.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'nullable|integer|min:1'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        $addQty = $request->input('qty', 1); // Default tambah 1 jika tidak ada input qty
        
        // Ambil varian dari request, jika tidak ada ambil varian pertama dari produk
        if ($request->filled('variant')) {
            $variant = $request->input('variant');
        } else {
            $firstVariant = $product->variants()->first();
            $variant = $firstVariant ? $firstVariant->name : 'Standard';
        }
        
        // Cek apakah produk sudah ada di keranjang dengan varian yang sama
        $cart = Cart::firstOrNew([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'variant'    => $variant
        ]);

        $currentQty = $cart->qty ?? 0;
        $requestedQty = $currentQty + $addQty;

        // Validasi Flash Sale Stock
        $activeFlashSaleItem = $product->getActiveFlashSaleItem();
        if ($activeFlashSaleItem) {
            if ($requestedQty > $activeFlashSaleItem->stock_allocated) {
                return redirect()->back()->with('error', 'Maaf, maksimal pembelian untuk barang Flash Sale ini adalah ' . $activeFlashSaleItem->stock_allocated . ' pcs.');
            }
        }

        // Validasi: Apakah total qty melebihi stok utama?
        if ($requestedQty <= $product->stock) {
            $cart->qty = $requestedQty;
            $cart->save();
            
            if ($request->has('buy_now')) {
                return redirect()->route('checkout.index');
            }
            
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }

        return redirect()->back()->with('error', 'Maaf, stok utama tidak mencukupi untuk jumlah tersebut.');
    }

    /**
     * Update jumlah produk (Tambah/Kurang).
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $product = $cart->product;

        if ($request->action === 'increase') {
            $activeFlashSaleItem = $product->getActiveFlashSaleItem();
            $maxQty = $activeFlashSaleItem ? min($product->stock, $activeFlashSaleItem->stock_allocated) : $product->stock;
            
            if ($cart->qty < $maxQty) {
                $cart->increment('qty');
            } else {
                return redirect()->back()->with('error', 'Stok maksimum atau batas kuota flash sale tercapai.');
            }
        } elseif ($request->action === 'decrease') {
            if ($cart->qty > 1) {
                $cart->decrement('qty');
            } else {
                $cart->delete();
            }
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Hapus produk dari keranjang.
     */
    public function destroy($id): RedirectResponse
    {
        if ($id === 'all') {
            Cart::where('user_id', Auth::id())->delete();
            return redirect()->back()->with('success', 'Keranjang telah dikosongkan.');
        } 
        
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }
}