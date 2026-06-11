<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan filter lengkap, sorting, dan pagination.
     */
    public function index(Request $request): View
    {
        $categories = Category::all();
        $brands = \App\Models\Brand::orderBy('name')->get();

        // 1. Membangun query dasar
        $query = Product::query()->with(['category', 'variants']);

        // 2. Menerapkan Filter (Faceted Search)
        $query->when($request->filled('category'), function ($q) use ($request) {
            $categories = (array)$request->category;
            // Jika 'all' ada di dalam array, berarti tidak perlu memfilter kategori
            if (!in_array('all', $categories)) {
                $q->where(function ($subQ) use ($categories) {
                    // Filter berdasarkan category_id
                    $subQ->whereHas('category', function ($catSub) use ($categories) {
                        $catSub->whereIn('slug', $categories);
                    });
                    
                    // Jika memilih 'gift-set', sertakan juga produk yang is_bundle = true
                    if (in_array('gift-set', $categories)) {
                        $subQ->orWhere('is_bundle', true);
                    }
                });
            }
        })
        ->when($request->filled('min_price'), fn($q) => $q->where('price', '>=', (float)$request->min_price))
        ->when($request->filled('max_price'), fn($q) => $q->where('price', '<=', (float)$request->max_price))
        ->when($request->filled('rating'),    fn($q) => $q->where('rating', '>=', (float)$request->rating))
        ->when($request->filled('skin_type'), fn($q) => $q->where('skin_type', 'like', '%' . $request->skin_type . '%'))
        
        // Filter Brands
        ->when($request->filled('brand'), function ($q) use ($request) {
            $q->whereIn('brand_id', (array)$request->brand);
        })
        
        // Filter Status Produk
        ->when($request->has('pre_order'), fn($q) => $q->where('is_pre_order', true))
        ->when($request->has('bundle'),    fn($q) => $q->where('is_bundle', true))
        ->when($request->has('in_stock'),  fn($q) => $q->where('stock', '>', 0))
        ->when($request->has('sale'), function ($q) {
            $q->whereHas('flashSaleItems', function ($sub) {
                $sub->whereHas('flashSale', function ($fs) {
                    $fs->where('is_active', true)
                       ->where('start_time', '<=', now())
                       ->where('end_time', '>=', now());
                });
            });
        })

        // Filter Beauty Profile
        ->when($request->filled('skin_type'), function ($q) use ($request) {
            $q->whereIn('skin_type', (array)$request->skin_type);
        })
        ->when($request->filled('hair_type'), function ($q) use ($request) {
            $q->whereIn('hair_type', (array)$request->hair_type);
        })
        
        // Filter Pencarian
        ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));

        // 3. Sorting Dinamis (Fixed: Assign hasil match ke $query)
        $query = match ($request->sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        // 4. Eksekusi Pagination
        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Menampilkan detail produk secara dinamis.
     */
    public function show(Product $product): View
    {
        $product->load(['images', 'variants', 'category']);
        $reviews = $product->reviews()->latest()->paginate(2);
        return view('products.show', compact('product', 'reviews'));
    }
}