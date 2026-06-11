<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman Checkout dengan data keranjang yang valid atau mode Beli Sekarang.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $isBuyNow = false;

        // MODE BELI SEKARANG
        if ($request->has('product_id') && $request->has('qty')) {
            $product = \App\Models\Product::findOrFail($request->product_id);
            $cartItem = new Cart();
            $cartItem->product_id = $product->id;
            $cartItem->qty = $request->qty;
            // Ambil varian dari request atau varian pertama dari database
            if ($request->filled('variant')) {
                $cartItem->variant = $request->variant;
            } else {
                $firstVariant = $product->variants()->first();
                $cartItem->variant = $firstVariant ? $firstVariant->name : 'Standard';
            }
            
            // Load relation secara manual untuk view
            $cartItem->setRelation('product', $product);
            
            $cartItems = collect([$cartItem]);
            $isBuyNow = true;
        } 
        // MODE KERANJANG NORMAL
        else {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->whereHas('product') 
                ->get();
        }

        // 2. Validasi jika kosong
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong atau produk tidak tersedia.');
        }

        // 3. Hitung subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->qty;
        });

        // 4. Proses Voucher
        $bestVoucher = null;
        $maxDiscountValue = 0;
        $voucherCode = $request->input('voucher_code');

        if ($voucherCode) {
            $bestVoucher = \App\Models\Voucher::where('code', $voucherCode)
                            ->where('is_active', true)
                            ->where('min_spend', '<=', $subtotal)
                            ->whereNotIn('id', function($query) {
                                $query->select('voucher_id')
                                      ->from('orders')
                                      ->where('user_id', Auth::id())
                                      ->whereNotNull('voucher_id');
                            })
                            ->first();
            
            if ($bestVoucher) {
                if ($bestVoucher->discount_type == 'percentage') {
                    $maxDiscountValue = $subtotal * ($bestVoucher->discount_amount / 100);
                } else {
                    $maxDiscountValue = $bestVoucher->discount_amount;
                }
                session()->flash('voucher_success', 'Voucher berhasil digunakan!');
            } else {
                session()->flash('voucher_error', 'Kode voucher tidak valid, sudah digunakan, atau minimal belanja belum tercapai.');
            }
        } else {
            // Cari otomatis jika tidak ada input manual
            $vouchers = \App\Models\Voucher::where('is_active', true)
                            ->where('min_spend', '<=', $subtotal)
                            ->whereNotIn('id', function($query) {
                                $query->select('voucher_id')
                                      ->from('orders')
                                      ->where('user_id', Auth::id())
                                      ->whereNotNull('voucher_id');
                            })
                            ->get();

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
        }

        $allVouchers = \App\Models\Voucher::where('is_active', true)
                        ->whereNotIn('id', function($query) {
                            $query->select('voucher_id')
                                  ->from('orders')
                                  ->where('user_id', Auth::id())
                                  ->whereNotNull('voucher_id');
                        })
                        ->get();

        $discount = $maxDiscountValue;
        $adminFee = 2000; 

        return view('checkout.index', compact('cartItems', 'subtotal', 'discount', 'adminFee', 'isBuyNow', 'bestVoucher', 'allVouchers'));
    }
}