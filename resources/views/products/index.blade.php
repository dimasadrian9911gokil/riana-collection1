@extends('layouts.app')

@section('title', 'Produk Skincare')

@section('content')
<!-- Include noUiSlider for the price range slider -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="offcanvas-md offcanvas-start border-0" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
                <div class="offcanvas-header border-bottom d-md-none" style="background: linear-gradient(135deg, #ffe6f2 0%, #ffb3c6 100%);">
                    <h5 class="offcanvas-title fw-bold text-dark" id="filterOffcanvasLabel"><i class="fas fa-filter text-pink me-2"></i>Filter Produk</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" data-bs-target="#filterOffcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <div class="card border-0 rounded-0 rounded-md-4 p-4 position-relative w-100" style="background: linear-gradient(180deg, #fff0f5 0%, #ffffff 100%); box-shadow: 0 10px 30px rgba(233, 30, 99, 0.1);">
                        <!-- Dekorasi -->
                        <div class="position-absolute top-0 start-0 w-100 rounded-top d-none d-md-block" style="height: 6px; background: linear-gradient(90deg, #FF6699, #ffb3c6);"></div>
                
                <form action="{{ route('products') }}" method="GET" id="filter-form">
                    <!-- Preserve sort param -->
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <!-- Category -->
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-tags text-pink me-2" style="font-size: 1.1rem;"></i>
                        <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: 0.5px;">Kategori</h6>
                    </div>
                    <ul class="list-unstyled mb-4 ps-2">
                        <li class="mb-2">
                            <div class="form-check custom-checkbox-pink">
                                <input class="form-check-input shadow-none border-pink" type="checkbox" name="category[]" value="all" id="cat-all" {{ in_array('all', (array)request('category')) || empty(request('category')) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                                <label class="form-check-label {{ empty(request('category')) || in_array('all', (array)request('category')) ? 'fw-bold text-pink' : 'text-muted' }} transition-03 hover-pink" for="cat-all" style="cursor: pointer;">All Skincare</label>
                            </div>
                        </li>
                        @foreach($categories as $cat)
                        <li class="mb-2">
                            <div class="form-check custom-checkbox-pink">
                                <input class="form-check-input shadow-none border-pink" type="checkbox" name="category[]" value="{{ $cat->slug ?? $cat->name }}" id="cat-{{ $cat->id }}" {{ in_array($cat->slug ?? $cat->name, (array)request('category')) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                                <label class="form-check-label text-muted transition-03 hover-pink" for="cat-{{ $cat->id }}" style="cursor: pointer;">{{ $cat->name }}</label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <hr class="mb-4" style="border-color: #ffb3c6; opacity: 0.5;">

                    <!-- Brands -->
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-crown text-pink me-2" style="font-size: 1.1rem;"></i>
                        <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: 0.5px;">Brand</h6>
                    </div>
                    
                    <div class="mb-4">
                        <div class="mb-3 position-relative">
                            <input type="text" class="form-control border-0 shadow-sm bg-white brand-search" placeholder="Cari Brand..." style="font-size: 0.85rem; border-radius: 20px; padding-left: 15px;">
                            <i class="fas fa-search position-absolute text-pink" style="right: 15px; top: 10px; font-size: 0.85rem;"></i>
                        </div>
                        <div class="brand-list overflow-auto pe-2 ps-2 custom-scrollbar" style="max-height: 180px;">
                            @foreach($brands as $brand)
                                <div class="form-check mb-2 custom-checkbox-pink brand-item">
                                    <input class="form-check-input shadow-none border-pink" type="checkbox" name="brand[]" value="{{ $brand->id }}" id="brand-{{ $brand->id }}" {{ in_array($brand->id, (array)request('brand')) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                                    <label class="form-check-label text-muted transition-03 hover-pink text-capitalize" style="font-size: 0.85rem; cursor: pointer;" for="brand-{{ $brand->id }}">{{ strtolower($brand->name) }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr class="mb-4" style="border-color: #ffb3c6; opacity: 0.5;">

                    <!-- Price -->
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-wallet text-pink me-2" style="font-size: 1.1rem;"></i>
                        <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: 0.5px;">Harga</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2 px-1">
                        <span class="fw-bold text-pink" style="font-size: 0.8rem;" id="slider-min-val">Rp0</span>
                        <span class="fw-bold text-pink" style="font-size: 0.8rem;" id="slider-max-val">Rp1.000.000++</span>
                    </div>
                    <div id="price-slider" class="mb-4 mt-3 mx-2 custom-slider"></div>
                    <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', 0) }}">
                    <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price', 1000000) }}">

                    <div class="form-check mb-4 mt-2 p-3 rounded-3" style="background: linear-gradient(45deg, #FF6699, #E91E63);">
                        <input class="form-check-input ms-1 shadow-none bg-white border-0" type="checkbox" name="sale" value="1" id="sale-check" {{ request('sale') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                        <label class="form-check-label fw-bold text-white ms-2" style="font-size: 0.95rem; cursor: pointer;" for="sale-check">
                            <i class="fas fa-fire-alt text-warning me-1"></i> Tampilkan DISKON
                        </label>
                    </div>
                    <hr class="mb-4" style="border-color: #ffb3c6; opacity: 0.5;">

                    <!-- Ratings -->
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-star text-warning me-2" style="font-size: 1.1rem;"></i>
                        <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: 0.5px;">Rating</h6>
                    </div>
                    <div class="ps-2">
                        <div class="form-check mb-2 d-flex align-items-center custom-radio-pink">
                            <input class="form-check-input shadow-none me-2 mt-0 border-pink" type="radio" name="rating" value="" id="rating-all" {{ empty(request('rating')) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                            <label class="form-check-label text-muted mt-1 hover-pink transition-03" style="font-size: 0.85rem; cursor: pointer;" for="rating-all">Semua Rating</label>
                        </div>
                        @foreach([5,4,3,2,1] as $r)
                        <div class="form-check mb-2 d-flex align-items-center custom-radio-pink">
                            <input class="form-check-input shadow-none me-2 mt-0 border-pink" type="radio" name="rating" value="{{$r}}" id="r{{$r}}" {{ request('rating') == $r ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                            <label class="form-check-label d-flex align-items-center mt-1" for="r{{$r}}" style="cursor: pointer;">
                                <div class="text-warning me-2 d-flex">
                                    @for($i=1; $i<=5; $i++)
                                        @if($i <= $r)
                                            <i class="fas fa-star" style="font-size: 0.8rem;"></i>
                                        @else
                                            <i class="far fa-star text-light-subtle" style="font-size: 0.8rem;"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-dark fw-bold" style="font-size: 0.85rem;">{{$r}}.0</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <hr class="mb-4" style="border-color: #ffb3c6; opacity: 0.5;">

                    <!-- Beauty Profile -->
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-magic text-pink me-2" style="font-size: 1.1rem;"></i>
                        <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: 0.5px;">Beauty Profile</h6>
                    </div>
                    
                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                        <p class="text-pink fw-bold mb-2" style="font-size: 0.75rem;"><i class="fas fa-leaf me-1"></i> KONDISI KULIT</p>
                        @php
                            $skinTypes = ['Normal', 'Kering', 'Berminyak', 'Sensitif', 'Kombinasi', 'Komedo', 'Aging', 'Kulit Kusam', 'Kantung Mata'];
                        @endphp
                        @foreach($skinTypes as $skin)
                        <div class="form-check mb-2 custom-checkbox-pink">
                            <input class="form-check-input shadow-none border-pink" type="checkbox" name="skin_type[]" value="{{ $skin }}" id="skin-{{ $loop->index }}" {{ in_array($skin, (array)request('skin_type')) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                            <label class="form-check-label text-muted hover-pink transition-03" style="font-size: 0.85rem; cursor: pointer;" for="skin-{{ $loop->index }}">{{ $skin }}</label>
                        </div>
                        @endforeach
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                        <p class="text-pink fw-bold mb-2" style="font-size: 0.75rem;"><i class="fas fa-wind me-1"></i> KONDISI RAMBUT</p>
                        @php
                            $hairTypes = ['Rambut Rontok', 'Dandruff', 'Dry Scalp'];
                        @endphp
                        @foreach($hairTypes as $hair)
                        <div class="form-check mb-2 custom-checkbox-pink">
                            <input class="form-check-input shadow-none border-pink" type="checkbox" name="hair_type[]" value="{{ $hair }}" id="hair-{{ $loop->index }}" {{ in_array($hair, (array)request('hair_type')) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit();">
                            <label class="form-check-label text-muted hover-pink transition-03" style="font-size: 0.85rem; cursor: pointer;" for="hair-{{ $loop->index }}">{{ $hair }}</label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Manual Buttons in case JS is disabled -->
                    <noscript>
                        <button type="submit" class="btn btn-pink rounded-pill w-100 mt-4 text-white fw-bold shadow">Terapkan Filter</button>
                    </noscript>
                    <a href="{{ route('products') }}" class="btn btn-outline-pink rounded-pill w-100 mt-2 fw-bold d-flex align-items-center justify-content-center gap-2" style="font-size: 0.9rem; border: 2px solid #ffb3c6;">
                        <i class="fas fa-sync-alt"></i> Reset All Filters
                    </a>
                </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Listing -->
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                
                <!-- Mobile Filter Toggle Button -->
                <button class="btn btn-pink d-md-none rounded-pill fw-bold px-4 shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" style="width: 100%;">
                    <i class="fas fa-filter me-2"></i> Filter Produk
                </button>

                <!-- Cute Banner Header -->
                <div class="cute-banner-header d-flex align-items-center px-4 py-3 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #ffe6f2 0%, #ffb3c6 100%); border: 2px dashed #ff99cc; position: relative; overflow: hidden;">
                    <!-- Sparkles Decoration -->
                    <i class="fas fa-star text-white position-absolute opacity-50" style="top: 10px; right: 20px; font-size: 0.8rem; animation: twinkle 1.5s infinite alternate;"></i>
                    <i class="fas fa-star text-white position-absolute opacity-50" style="bottom: 15px; left: 15px; font-size: 0.6rem; animation: twinkle 2s infinite alternate;"></i>
                    
                    <div class="bg-white rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm" style="width: 45px; height: 45px;">
                        <i class="fas fa-shopping-bag text-pink fs-5"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: 0.5px;">Koleksi Produk</h4>
                        <p class="mb-0 text-pink fw-bold" style="font-size: 0.8rem;">Temukan favoritmu di sini ✨</p>
                    </div>
                </div>
                
                <form action="{{ route('products') }}" method="GET" class="d-flex">
                    @foreach(request()->except('sort') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <select name="sort" class="form-select rounded-pill px-4 shadow-sm border-pink" style="font-size: 0.9rem; height: fit-content; min-width: 180px;" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>✨ Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💸 Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💎 Harga Tertinggi</option>
                    </select>
                </form>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse($products as $product)
                    <div class="col">
                        <div class="card h-100 product-card-v2 bg-white">
                            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                                <!-- Image Container -->
                                <div class="position-relative text-center d-flex align-items-center justify-content-center" style="height: 250px; background-color: #fff;">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid p-3 transition-transform" alt="{{ $product->name }}" style="object-fit: contain; max-height: 100%; width: auto;">
                                    
                                    <!-- Badges -->
                                    @if($product->final_price < $product->price && $product->price > 0)
                                        @php
                                            $discountPercent = round((($product->price - $product->final_price) / $product->price) * 100);
                                        @endphp
                                        <div class="position-absolute top-0 start-0 badge-discount text-white fw-bold px-2 py-1 m-2" style="font-size: 0.75rem; border-radius: 2px;">
                                            {{ $discountPercent }}%
                                        </div>
                                    @endif

                                    <button class="btn btn-link position-absolute top-0 end-0 p-2 shadow-none heart-btn" onclick="event.preventDefault();">
                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="heart-icon"><path d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/></svg>
                                    </button>
                                    
                                    <!-- Online Deals Banner -->
                                    <div class="position-absolute bottom-0 w-100 py-1 banner-deals">
                                        <span class="text-dark fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">ONLINE DEALS</span>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body p-3 d-flex flex-column text-start">
                                    <div class="mb-2 text-center d-flex justify-content-center flex-wrap gap-1">
                                        @if(isset($product->variants) && $product->variants->count() > 0)
                                            @foreach($product->variants->take(2) as $variant)
                                                <span class="variant-badge">{{ $variant->name }}</span>
                                            @endforeach
                                            @if($product->variants->count() > 2)
                                                <span class="variant-badge">+{{ $product->variants->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="variant-badge">150 ml</span>
                                            <span class="variant-badge">50 ml</span>
                                        @endif
                                    </div>

                                    <p class="fw-bold mb-1 text-uppercase text-dark" style="font-size: 0.8rem; letter-spacing: 0.5px;">{{ $product->brand->name ?? 'COSRX' }}</p>
                                    <p class="text-muted mb-2 lh-sm" style="font-size: 0.85rem;">{{ $product->name }}</p>
                                    
                                    <div class="mt-auto">
                                        @php
                                            $hasVariantPrices = isset($product->variants) && $product->variants->where('price_modifier', '>', 0)->count() > 0;
                                            $minPrice = $product->final_price;
                                            $maxPrice = $product->final_price;
                                            if ($hasVariantPrices) {
                                                $prices = $product->variants->pluck('price_modifier')->filter(fn($p) => $p > 0)->toArray();
                                                if ($product->variants->where('price_modifier', '<=', 0)->count() > 0 || $product->variants->whereNull('price_modifier')->count() > 0) {
                                                    $prices[] = $product->final_price;
                                                }
                                                $minPrice = !empty($prices) ? min($prices) : $product->final_price;
                                                $maxPrice = !empty($prices) ? max($prices) : $product->final_price;
                                            }
                                        @endphp

                                        @if($hasVariantPrices && $minPrice != $maxPrice)
                                            <div class="mb-1">
                                                <span class="text-price fw-bold" style="font-size: 0.9rem;">Rp{{ number_format($minPrice, 0, ',', '.') }} - Rp{{ number_format($maxPrice, 0, ',', '.') }}</span>
                                            </div>
                                        @elseif($product->final_price < $product->price)
                                            <div class="d-flex align-items-center flex-wrap mb-1">
                                                <span class="text-price fw-bold me-2" style="font-size: 0.9rem;">Rp{{ number_format($product->final_price, 0, ',', '.') }}</span>
                                                <span class="text-muted text-decoration-line-through" style="font-size: 0.75rem;">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                            </div>
                                        @else
                                            <div class="mb-1">
                                                <span class="text-price fw-bold" style="font-size: 0.9rem;">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex align-items-center">
                                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16" class="me-1 text-price"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                            <span class="text-dark" style="font-size: 0.8rem;">{{ number_format($product->rating, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">Produk tidak ditemukan.</h5>
                        <a href="{{ route('products') }}" class="btn btn-pink mt-3 rounded-pill text-white">Reset Pencarian</a>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-5 d-flex justify-content-center custom-pagination">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize Price Slider
    var slider = document.getElementById('price-slider');
    var minPriceInput = document.getElementById('min_price');
    var maxPriceInput = document.getElementById('max_price');
    var minVal = document.getElementById('slider-min-val');
    var maxVal = document.getElementById('slider-max-val');

    noUiSlider.create(slider, {
        start: [minPriceInput.value || 0, maxPriceInput.value || 1000000],
        connect: true,
        range: {
            'min': 0,
            'max': 1000000
        },
        step: 10000,
        format: {
            to: function (value) { return Math.round(value); },
            from: function (value) { return Number(value); }
        }
    });

    slider.noUiSlider.on('update', function (values, handle) {
        var value = values[handle];
        if (handle) {
            maxPriceInput.value = value;
            maxVal.innerHTML = 'Rp' + new Intl.NumberFormat('id-ID').format(value) + (value >= 1000000 ? '++' : '');
        } else {
            minPriceInput.value = value;
            minVal.innerHTML = 'Rp' + new Intl.NumberFormat('id-ID').format(value);
        }
    });

    slider.noUiSlider.on('change', function () {
        document.getElementById('filter-form').submit();
    });

    // 2. Brand Search Functionality
    var brandSearchInput = document.querySelector('.brand-search');
    if(brandSearchInput) {
        brandSearchInput.addEventListener('input', function(e) {
            let term = e.target.value.toLowerCase();
            let items = document.querySelectorAll('.brand-item');
            items.forEach(function(item) {
                let text = item.querySelector('label').innerText.toLowerCase();
                if(text.includes(term)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>

<style>
    /* Styling adjustments for beautiful UI */
    
    .variant-badge {
        background-color: #fff0f5;
        color: #d81b60;
        border: 1px solid #f8bbd0;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 6px;
        padding: 3px 8px;
        display: inline-block;
    }

    /* Card Redesign */
    .product-card-v2 {
        border: 2px solid #ffe6f2;
        border-radius: 20px !important;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .product-card-v2:hover { 
        border-color: #ff99bb;
        box-shadow: 0 15px 30px rgba(255, 102, 153, 0.2);
        transform: translateY(-8px);
    }
    .product-card-v2:hover .transition-transform { transform: scale(1.05); }
    .transition-transform { transition: 0.4s ease-in-out; }
    .badge-discount { background-color: #E91E63; }
    .text-price { color: #E91E63; }
    .banner-deals { background-color: #fce4ec; }
    .heart-icon { color: #ccc; transition: 0.2s; }
    .heart-btn:hover .heart-icon { fill: #E91E63; color: #E91E63; }
    
    .text-pink { color: #FF6699; }
    .btn-pink { background-color: #FF6699; border-color: #FF6699; transition: 0.3s; }
    .btn-pink:hover { background-color: #e65c8a; border-color: #e65c8a; color: white; }
    .btn-outline-pink { border: 1.5px solid #FF6699; color: #FF6699; }
    .btn-outline-pink:hover { background-color: #FF6699; color: white; border-color: #FF6699; }
    
    /* Checkbox & Radio Customization */
    .form-check-input:checked { background-color: #FF6699; border-color: #FF6699; }
    .form-check-input:focus { border-color: #ff99bb; box-shadow: 0 0 0 0.25rem rgba(255, 102, 153, 0.25); }

    /* Custom Scrollbar for Brands */
    .brand-list::-webkit-scrollbar { width: 6px; }
    .brand-list::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .brand-list::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 4px; }
    .brand-list::-webkit-scrollbar-thumb:hover { background: #FF6699; }

    /* noUiSlider Customization */
    .noUi-connect { background: #FF6699; }
    .noUi-handle { border-radius: 50%; background: #FF6699; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }
    .noUi-handle:before, .noUi-handle:after { display: none; }
    .noUi-target { background: #e9ecef; border: none; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); height: 6px; }
    .noUi-horizontal .noUi-handle { width: 22px; height: 22px; right: -11px; top: -8px; cursor: pointer; }
    .noUi-horizontal .noUi-handle:hover { transform: scale(1.1); transition: 0.2s; }
</style>
<style>
    /* Styling Tambahan untuk Filter Sidebar yang lebih Estetik */
    .transition-03 { transition: 0.3s; }
    .hover-pink:hover { color: #E91E63 !important; }
    
    .custom-checkbox-pink .form-check-input:checked {
        background-color: #E91E63;
        border-color: #E91E63;
    }
    .custom-radio-pink .form-check-input:checked {
        background-color: #E91E63;
        border-color: #E91E63;
    }
    .border-pink:focus {
        border-color: #ffb3c6;
        box-shadow: 0 0 0 0.25rem rgba(233, 30, 99, 0.25);
    }
    
    .custom-tab-btn.active {
        color: #E91E63 !important;
        border-bottom: 2px solid #E91E63 !important;
    }
    .custom-tab-btn:hover {
        color: #E91E63 !important;
    }
    
    .btn-outline-pink {
        color: #E91E63;
        border-color: #E91E63;
        background-color: transparent;
        transition: 0.3s;
    }
    .btn-outline-pink:hover {
        color: #fff;
        background-color: #E91E63;
        border-color: #E91E63;
    }
    
    .custom-slider .noUi-connect {
        background: #E91E63 !important;
    }
    .custom-slider .noUi-handle {
        border: 2px solid #fff;
        background: #FF6699;
        box-shadow: 0 2px 5px rgba(233, 30, 99, 0.4);
        border-radius: 50%;
    }
    .custom-slider .noUi-handle::before,
    .custom-slider .noUi-handle::after {
        display: none;
    }

    /* Custom Scrollbar for Brand List */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #fff0f5; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ffb3c6; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #FF6699; 
    }

    /* Custom Pagination Styling */
    .custom-pagination .pagination {
        gap: 8px;
        margin-bottom: 0;
    }
    .custom-pagination .page-item .page-link {
        border-radius: 50% !important;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #E91E63;
        border: 1.5px solid #ffb3c6;
        background-color: white;
        transition: 0.3s all;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #FF6699, #E91E63);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 12px rgba(233, 30, 99, 0.3);
        transform: scale(1.05);
    }
    .custom-pagination .page-item:not(.active) .page-link:hover {
        background-color: #ffe6f2;
        color: #E91E63;
        border-color: #FF6699;
        transform: translateY(-2px);
    }
    .custom-pagination .page-item.disabled .page-link {
        color: #d1d1d1;
        border-color: #f1f1f1;
        background-color: #fcfcfc;
        box-shadow: none;
    }

    @keyframes twinkle {
        0% { transform: scale(1); opacity: 0.3; }
        100% { transform: scale(1.2); opacity: 0.8; }
    }
</style>
@endsection