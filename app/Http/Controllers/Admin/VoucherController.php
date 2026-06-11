<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers',
            'name' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
        ]);

        Voucher::create(array_merge($request->all(), ['is_active' => $request->has('is_active')]));

        return back()->with('success', 'Voucher baru berhasil ditambahkan!');
    }

    public function toggle(Request $request, Voucher $voucher)
    {
        $voucher->update(['is_active' => !$voucher->is_active]);
        return back()->with('success', 'Status voucher berhasil diubah!');
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
        ]);

        $voucher->update(array_merge($request->all(), ['is_active' => $request->has('is_active')]));

        return back()->with('success', 'Voucher berhasil diperbarui!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus!');
    }
}
