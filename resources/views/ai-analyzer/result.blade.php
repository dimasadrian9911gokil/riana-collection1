@extends('layouts.app')

@section('title', 'Hasil Analisis AI - Riana Collection')

@section('content')
<style>
    .result-hero {
        background: radial-gradient(circle at top right, #e0c3fc 0%, #8ec5fc 100%);
        padding: 60px 0;
        text-align: center;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
        margin-bottom: 50px;
    }
    .score-circle {
        width: 150px;
        height: 150px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        font-size: 4rem;
        border: 5px solid #fff;
    }
    .product-card {
        transition: 0.3s;
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
</style>

@php
    $typeInfo = [
        'kering' => [
            'icon' => '💧',
            'title' => 'Kulit Kering (Dry Skin)',
            'desc' => 'Kulit Anda cenderung kekurangan kelembapan alami, terasa kencang, dan mungkin sedikit bersisik.',
            'tips' => 'Fokus pada hidrasi mendalam. Gunakan pembersih wajah yang lembut, toner yang menghidrasi (hydrating toner), dan pelembap bertekstur krim tebal. Hindari mencuci muka dengan air terlalu panas.'
        ],
        'berminyak' => [
            'icon' => '✨',
            'title' => 'Kulit Berminyak (Oily Skin)',
            'desc' => 'Kelenjar sebasea Anda memproduksi minyak berlebih, membuat wajah cepat mengkilap dan pori-pori tampak lebih besar.',
            'tips' => 'Rutin gunakan double cleansing. Pilih produk bertekstur gel yang ringan dan non-comedogenic (tidak menyumbat pori). Kandungan seperti Salicylic Acid (BHA) sangat baik untuk mengontrol minyak.'
        ],
        'kombinasi' => [
            'icon' => '🌸',
            'title' => 'Kulit Kombinasi (Combination Skin)',
            'desc' => 'Kondisi kulit Anda campur aduk: area T-Zone (dahi, hidung, dagu) berminyak, sedangkan area pipi normal atau kering.',
            'tips' => 'Gunakan pelembap bertekstur lotion ringan ke seluruh wajah. Jika perlu, aplikasikan masker clay (lumpur) hanya di area T-Zone untuk menyerap minyak tanpa membuat pipi kering.'
        ],
        'sensitif' => [
            'icon' => '🌿',
            'title' => 'Kulit Sensitif (Sensitive Skin)',
            'desc' => 'Kulit Anda sangat reaktif terhadap faktor lingkungan atau bahan kosmetik tertentu, mudah memerah dan gatal.',
            'tips' => 'Pilih produk dengan label "Hypoallergenic" atau "Fragrance-Free". Cari kandungan yang menenangkan (soothing) seperti Ceramide, Centella Asiatica, atau Panthenol untuk memperbaiki skin barrier.'
        ]
    ];

    $info = $typeInfo[$skinType];
@endphp

<div class="result-hero shadow-sm">
    <div class="container">
        <h5 class="text-uppercase fw-bold text-primary mb-3">HASIL ANALISIS AI</h5>
        
        <div class="score-circle">
            {{ $info['icon'] }}
        </div>
        
        <h1 class="display-5 fw-bold text-dark mb-3">{{ $info['title'] }}</h1>
        <p class="fs-5 text-dark opacity-75 max-w-75 mx-auto" style="max-width: 600px;">
            {{ $info['desc'] }}
        </p>
    </div>
</div>

<div class="container mb-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: #f8f9fa; border-left: 5px solid #00f2fe !important;">
                <h4 class="fw-bold"><i class="fas fa-lightbulb text-warning me-2"></i>Saran Perawatan Ahli (Dermatologist Tips)</h4>
                <p class="mb-0 fs-6 mt-2 text-muted">{{ $info['tips'] }}</p>
            </div>
        </div>
    </div>

    <div class="text-center mb-4 mt-5">
        <h2 class="fw-bold">Rekomendasi Produk AI Untukmu</h2>
        <p class="text-muted">Produk-produk ini memiliki formulasi yang paling cocok untuk tipe kulit {{ strtolower($info['title']) }}.</p>
    </div>

    <div class="row g-4 justify-content-center">
        @forelse($recommendedProducts as $product)
        <div class="col-lg-3 col-md-4 col-6">
            <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                <div class="card product-card shadow-sm h-100">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                    <div class="card-body p-3 text-center">
                        <span class="badge bg-light text-dark mb-2">{{ $product->category->name ?? 'Produk' }}</span>
                        <h6 class="fw-bold text-dark text-truncate">{{ $product->name }}</h6>
                        <p class="text-pink fw-bold mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">Maaf, saat ini belum ada produk khusus yang tersedia untuk tipe kulit ini.</p>
            <a href="{{ route('products') }}" class="btn btn-primary rounded-pill px-4 mt-2">Lihat Semua Produk</a>
        </div>
        @endforelse
    </div>
    
    <div class="text-center mt-5">
        <a href="{{ route('ai.analyzer') }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="fas fa-redo me-2"></i>Ulangi Analisis</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi Suara Robot AI (Text-to-Speech)
    function speakAI(text) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel(); 
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID'; 
            utterance.rate = 0.9;     
            utterance.pitch = 1.2;    
            window.speechSynthesis.speak(utterance);
        }
    }

    // Ambil data dari PHP menggunakan json_encode agar aman dalam string JS
    const skinTitle = {!! json_encode($info['title']) !!};
    const skinTips = {!! json_encode($info['tips']) !!};
    
    // Hilangkan teks dalam kurung seperti (Dry Skin) agar terdengar natural saat dibaca
    const cleanTitle = skinTitle.replace(/\s*\(.*?\)\s*/g, '');

    // Gabungkan menjadi satu kalimat laporan
    const reportText = "Hasil analisis selesai. Tipe kulit Anda terdeteksi sebagai " + cleanTitle + ". " + skinTips;

    // Tunggu 1 detik agar halaman selesai render, lalu mulai bicara
    setTimeout(() => {
        speakAI(reportText);
    }, 1000);
});
</script>
@endsection
