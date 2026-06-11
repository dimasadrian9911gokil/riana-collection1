<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000'
        ]);

        Review::create([
            'product_id' => $product->id,
            'reviewer_name' => auth()->user()->name,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        // Hitung ulang rata-rata rating
        $avgRating = $product->reviews()->avg('rating');
        $product->update([
            'rating' => $avgRating
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim dan tersimpan di database.');
    }
}
