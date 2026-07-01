@extends('layouts.app')

@section('title', 'Brand Favorit')

@section('content')

<!-- HERO BANNER -->
<section class="position-relative py-5 mb-5" style="background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 100%); overflow: hidden;">
    <!-- Dekorasi Bulat -->
    <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 300px; height: 300px; top: -100px; left: -50px;"></div>
    <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 200px; height: 200px; bottom: -50px; right: 5%;"></div>
    <div class="position-absolute rounded-circle bg-white opacity-25" style="width: 100px; height: 100px; top: 20%; left: 40%;"></div>
    
    <div class="container position-relative z-index-1 text-center py-4 py-md-5">
        <span class="badge bg-white text-danger px-4 py-2 fs-6 rounded-pill mb-3 shadow-sm" style="letter-spacing: 1px;">
            <i class="fas fa-gem me-2"></i> KUALITAS TERBAIK
        </span>
        <h1 class="display-5 display-md-3 fw-bold text-white mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
            Brand Favorit ✨
        </h1>
        <p class="fs-5 fs-md-4 text-white opacity-75 fw-light mx-auto" style="max-width: 600px;">
            Jelajahi koleksi eksklusif dari brand kecantikan terkemuka dan temukan rahasia kulit bersinarmu di Riana Collection.
        </p>
    </div>
</section>

<div class="container pb-5">
    
    <div class="row g-4 justify-content-center">
        @php
        $gradients = [
            'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
            'linear-gradient(120deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%)',
            'linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%)',
            'linear-gradient(120deg, #f6d365 0%, #fda085 100%)',
            'linear-gradient(to right, #fa709a 0%, #fee140 100%)'
        ];
        
        $index = 0;
        @endphp

        @foreach($brands as $brand)
        @php
            $bg = $gradients[$index % count($gradients)];
            $initial = substr($brand->name, 0, 1);
            $index++;
        @endphp

        <div class="col-lg-3 col-md-4 col-6">
            <a href="{{ url('/products?brand[]=' . $brand->id) }}" class="text-decoration-none">
                <div class="card h-100 border-0 rounded-4 brand-card position-relative overflow-hidden bg-white">
                    <!-- Aksen Garis Atas -->
                    <div style="height: 60px; background: {{ $bg }}; width: 100%; position: absolute; top: 0; left: 0;"></div>
                    
                    <div class="card-body text-center pt-4 px-2 px-md-4 pb-3 pb-md-4 mt-2 position-relative z-index-1">
                        <!-- Logo Inisial -->
                        <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3 bg-white" 
                             style="width: 80px; height: 80px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 3px solid white;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle w-100 h-100" style="background: {{ $bg }}; color: white; font-size: 2rem; font-weight: 800; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
                                {{ $initial }}
                            </div>
                        </div>

                        <h5 class="fw-bolder text-dark mb-1 text-uppercase" style="letter-spacing: 0.5px;">
                            {{ $brand->name }}
                        </h5>
                        <p class="text-muted small mb-4" style="font-size: 0.8rem; height: 40px; overflow: hidden; text-overflow: ellipsis;">
                            {{ $brand->description ?? 'Official Store' }}
                        </p>

                        <div class="d-flex align-items-center justify-content-center text-pink fw-bold brand-link" style="font-size: 0.9rem;">
                            <span class="d-none d-sm-inline">Jelajahi Produk</span><span class="d-sm-none">Lihat</span> <i class="fas fa-arrow-right ms-1 ms-md-2 transition-icon"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

</div>

<style>
    .brand-card {
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .brand-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(255, 102, 153, 0.15);
    }
    .text-pink {
        color: #FF6699;
    }
    .brand-link {
        transition: 0.3s;
    }
    .brand-card:hover .brand-link {
        color: #e64a82 !important;
    }
    .transition-icon {
        transition: transform 0.3s ease;
    }
    .brand-card:hover .transition-icon {
        transform: translateX(5px);
    }
</style>

@endsection