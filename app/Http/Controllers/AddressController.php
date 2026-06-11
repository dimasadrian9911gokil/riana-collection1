<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AddressController extends Controller
{
    /**
     * Menampilkan daftar alamat pengguna.
     */
    public function index(): View
    {
        $addresses = Address::where('user_id', Auth::id())->latest('is_default')->get();
        return view('address.index', compact('addresses'));
    }

    /**
     * Menampilkan form edit alamat (Fungsi yang tadi hilang).
     */
    public function edit(Address $address): View
    {
        $this->authorizeAccess($address);
        return view('address.edit', compact('address'));
    }

    /**
     * Menyimpan alamat baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateAddress($request);
        $data['user_id'] = Auth::id();
        $data['is_default'] = $request->has('is_default');

        if ($data['is_default']) {
            $this->resetDefaultAddresses();
        }

        Address::create($data);
        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Menetapkan alamat sebagai utama.
     */
    public function setPrimary(Address $address): RedirectResponse
    {
        $this->authorizeAccess($address);
        
        $this->resetDefaultAddresses();
        $address->update(['is_default' => true]);

        return back()->with('success', 'Alamat utama berhasil diperbarui.');
    }

    /**
     * Memperbarui alamat yang ada.
     */
    public function update(Request $request, Address $address): RedirectResponse
    {
        $this->authorizeAccess($address);
        $data = $this->validateAddress($request);
        $data['is_default'] = $request->has('is_default');

        if ($data['is_default']) {
            $this->resetDefaultAddresses();
        }

        $address->update($data);
        return redirect()->route('address.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Menghapus alamat.
     */
    public function destroy(Address $address): RedirectResponse
    {
        $this->authorizeAccess($address);
        
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            Address::where('user_id', Auth::id())->latest()->first()?->update(['is_default' => true]);
        }

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    // --- Private Helper Methods ---

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'recipient_name' => 'required|max:255',
            'phone'          => 'required|max:20',
            'address'        => 'required',
            'province'       => 'required',
            'city'           => 'required',
            'label'          => 'nullable|string',
            'district'       => 'nullable|string',
            'postal_code'    => 'nullable|string',
        ]);
    }

    private function resetDefaultAddresses(): void
    {
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
    }

    private function authorizeAccess(Address $address): void
    {
        if ((int)$address->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}