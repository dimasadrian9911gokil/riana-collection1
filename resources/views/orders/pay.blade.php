@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran')

@section('content')
<div class="container py-5 text-center" style="min-height: 60vh;">
    <h2 class="fw-bold mb-4">Selesaikan Pembayaran Anda</h2>
    <p class="text-muted mb-4">Mohon jangan tutup halaman ini, jendela pembayaran akan segera muncul.</p>
    
    <div class="spinner-border text-pink mb-4" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>

    <div>
        <button id="pay-button" class="btn btn-pink rounded-pill px-4 py-2 fw-bold shadow-sm" style="display: none;">
            Klik di sini jika popup tidak muncul
        </button>
    </div>
</div>

<!-- Menggunakan Sandbox Midtrans Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var payButton = document.getElementById('pay-button');
        
        function triggerPayment() {
            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result){
                    // Arahkan ke halaman sukses
                    window.location.href = "{{ route('orders.success', $order->id) }}";
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!");
                    window.location.href = "{{ route('orders.show', $order->id) }}";
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                    window.location.href = "{{ route('orders.show', $order->id) }}";
                },
                onClose: function(){
                    alert("Anda menutup halaman pembayaran sebelum menyelesaikannya.");
                    window.location.href = "{{ route('orders.show', $order->id) }}";
                }
            });
        }

        // Trigger otomatis saat load
        setTimeout(function() {
            triggerPayment();
            payButton.style.display = 'inline-block';
        }, 1000);

        payButton.onclick = function() {
            triggerPayment();
        };
    });
</script>

<style>
    .text-pink { color: #E91E63; }
    .btn-pink { background-color: #E91E63; border-color: #E91E63; color: white; }
    .btn-pink:hover { background-color: #c2185b; border-color: #c2185b; color: white; }
</style>
@endsection
