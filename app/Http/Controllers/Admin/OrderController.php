<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        
        $orders = Order::with('user')
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)->withQueryString();
            
        // Check Expiry for pagination items
        foreach ($orders as $order) {
            $order->checkExpiry();
        }
            
        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,menunggu_verifikasi,sudah_dibayar,dikemas,dikirim,selesai,dibatalkan',
            'tracking_number' => 'nullable|string|max:255'
        ]);

        $oldStatus = $order->status;

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number
        ]);

        if ($oldStatus !== $request->status) {
            $order->user->notify(new \App\Notifications\OrderStatusUpdated($order, $oldStatus, $request->status));
        }

        return back()->with('success', 'Status pesanan ' . $order->invoice . ' berhasil diperbarui!');
    }

    public function show($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        $order->checkExpiry();
        return view('admin.orders.show', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return back()->with('success', 'Pesanan ' . $order->invoice . ' berhasil dihapus.');
    }
}
