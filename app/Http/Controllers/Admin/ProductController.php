<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'skin_type' => 'nullable|string|max:255',
            'how_to_use' => 'nullable|string',
            'ingredients' => 'nullable|string',
        ]);

        $data = $request->except(['image', 'gallery']);

        // Upload Main Image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product = Product::create($data);

        // Upload Gallery Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPath = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $galleryPath
                ]);
            }
        }

        // Simpan Variasi Produk
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $variant) {
                if (!empty($variant['name'])) {
                    $variantImagePath = null;
                    if (isset($variant['image']) && $variant['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $variantImagePath = $variant['image']->store('products/variants', 'public');
                    }

                    \App\Models\ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                        'price_modifier' => $variant['price_modifier'] ?? 0,
                        'stock' => isset($variant['stock']) && $variant['stock'] !== '' ? $variant['stock'] : $product->stock,
                        'image_path' => $variantImagePath
                    ]);
                }
            }
        }

        $users = \App\Models\User::where('role', 'user')->get();
        \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\SystemNotification(
            'Produk Baru Telah Hadir!',
            "Ayo cek koleksi terbaru kami: {$product->name}",
            'fa-box-open',
            'text-success',
            route('products.show', $product->id)
        ));

        return redirect()->route('admin.products.index')->with('success', 'Produk baru dan variasinya berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'skin_type' => 'nullable|string|max:255',
            'how_to_use' => 'nullable|string',
            'ingredients' => 'nullable|string',
        ]);

        $data = $request->except(['image', 'gallery']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $oldStock = $product->stock;
        $product->update($data);

        if ($product->stock > $oldStock) {
            $users = \App\Models\User::where('role', 'user')->get();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\SystemNotification(
                'Restock Produk!',
                "Hore! Stok produk {$product->name} telah ditambah. Yuk buruan beli sebelum kehabisan!",
                'fa-box',
                'text-primary',
                route('products.show', $product->id)
            ));
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPath = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $galleryPath
                ]);
            }
        }

        // Kelola Variasi Produk
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id']) && !empty($variantData['id'])) {
                    // Update atau Hapus variasi lama
                    $variant = \App\Models\ProductVariant::find($variantData['id']);
                    if ($variant) {
                        if (isset($variantData['delete']) && $variantData['delete'] == '1') {
                            if ($variant->image_path) {
                                Storage::disk('public')->delete($variant->image_path);
                            }
                            $variant->delete();
                        } else {
                            $variantUpdateData = [
                                'name' => $variantData['name'],
                                'price_modifier' => $variantData['price_modifier'] ?? 0,
                                'stock' => isset($variantData['stock']) && $variantData['stock'] !== '' ? $variantData['stock'] : $product->stock
                            ];
                            
                            if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                                if ($variant->image_path) {
                                    Storage::disk('public')->delete($variant->image_path);
                                }
                                $variantUpdateData['image_path'] = $variantData['image']->store('products/variants', 'public');
                            }

                            $variant->update($variantUpdateData);
                        }
                    }
                } else {
                    // Tambah variasi baru
                    if (!empty($variantData['name']) && (!isset($variantData['delete']) || $variantData['delete'] != '1')) {
                        $variantImagePath = null;
                        if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $variantImagePath = $variantData['image']->store('products/variants', 'public');
                        }

                        \App\Models\ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $variantData['name'],
                            'price_modifier' => $variantData['price_modifier'] ?? 0,
                            'stock' => isset($variantData['stock']) && $variantData['stock'] !== '' ? $variantData['stock'] : $product->stock,
                            'image_path' => $variantImagePath
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk dan variasinya berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->latest()->paginate(15);
        
        return view('admin.products.index', compact('products', 'search'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $oldStock = $product->stock;

        $product->update([
            'stock' => $request->stock
        ]);

        if ($request->stock > $oldStock) {
            $users = \App\Models\User::role('user')->get();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\SystemNotification(
                'Restock Produk!',
                "Hore! Stok produk {$product->name} telah ditambah. Yuk buruan beli sebelum kehabisan!",
                'fa-box',
                'text-primary',
                route('products.show', $product->id)
            ));
        }

        return back()->with('success', 'Stok produk '.$product->name.' berhasil diperbarui!');
    }
}
