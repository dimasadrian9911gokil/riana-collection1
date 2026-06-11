<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $customers = User::where(function($q) {
            $q->whereHas('roles', function($roleQuery) {
                $roleQuery->where('name', '!=', 'admin');
            })->orWhereDoesntHave('roles');
        })
        ->withCount('orders')
        ->withSum(['orders as total_spent' => function($q) {
            $q->where('status', 'selesai');
        }], 'total')
        ->when($search, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");
            });
        })->latest()->paginate(15);
        
        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function show(User $customer)
    {
        $customer->load(['addresses', 'orders' => function($q) {
            $q->latest()->take(5);
        }]);
        
        return view('admin.customers.show', compact('customer'));
    }

    public function toggle(User $customer)
    {
        if ($customer->hasRole('admin')) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun admin.');
        }

        $customer->update(['is_active' => !$customer->is_active]);
        return back()->with('success', 'Status pelanggan berhasil diubah!');
    }

    public function destroy(User $customer)
    {
        if ($customer->hasRole('admin')) {
            return back()->with('error', 'Tidak dapat menghapus akun admin.');
        }

        $customer->delete();
        return back()->with('success', 'Akun pelanggan berhasil dihapus secara permanen.');
    }
}
