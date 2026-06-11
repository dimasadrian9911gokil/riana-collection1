<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Tampilkan halaman wishlist pengguna.
     */
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('product')->get();
        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Tambah/Hapus produk dari wishlist (AJAX/API Endpoint).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $wishlist = $user->wishlists()->where('product_id', $productId)->first();

        if ($wishlist) {
            // Hapus jika sudah ada
            $wishlist->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Produk dihapus dari wishlist'
            ]);
        } else {
            // Tambahkan jika belum ada
            $user->wishlists()->create([
                'product_id' => $productId
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Produk ditambahkan ke wishlist'
            ]);
        }
    }

    /**
     * Hapus produk dari wishlist secara langsung via form submission.
     */
    public function destroy($id)
    {
        $wishlist = Auth::user()->wishlists()->findOrFail($id);
        $wishlist->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari wishlist.');
    }
}
