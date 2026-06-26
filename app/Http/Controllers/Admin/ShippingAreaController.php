<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingArea;
use Illuminate\Http\Request;

class ShippingAreaController extends Controller
{
    public function index()
    {
        $areas = ShippingArea::orderBy('name')->get();
        return view('admin.shipping_areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_areas,name',
            'cost' => 'required|numeric|min:0',
        ]);

        ShippingArea::create($validated);
        return back()->with('success', 'Area pengiriman berhasil ditambahkan.');
    }

    public function update(Request $request, ShippingArea $shippingArea)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_areas,name,' . $shippingArea->id,
            'cost' => 'required|numeric|min:0',
        ]);

        $shippingArea->update($validated);
        return back()->with('success', 'Area pengiriman berhasil diperbarui.');
    }

    public function toggle(ShippingArea $shippingArea)
    {
        $shippingArea->update(['is_active' => !$shippingArea->is_active]);
        return back()->with('success', 'Status area pengiriman berhasil diubah.');
    }

    public function destroy(ShippingArea $shippingArea)
    {
        $shippingArea->delete();
        return back()->with('success', 'Area pengiriman berhasil dihapus.');
    }
}
