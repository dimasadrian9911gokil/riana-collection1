@extends('layouts.app')

@section('title', $product->name . ' - Riana Collection')

@section('content')
<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.85rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products') }}" class="text-decoration-none text-muted">Shop By Departments</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products', ['category' => $product->category->slug ?? 'skincare']) }}" class="text-decoration-none text-muted">{{ $product->category->name ?? 'Skincare' }}</a></li>
            <li class="breadcrumb-item active text-dark" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- 1. LEFT COLUMN: IMAGE GALLERY -->
        <div class="col-lg-4 mb-4">
            <div class="position-relative text-center mb-3">
                <!-- Main Image -->
                <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" 
                     class="img-fluid p-4" 
                     alt="{{ $product->name }}" 
                     style="width: 100%; height: 400px; object-fit: contain; background: #fff; border-radius: 8px;">
                
                @if($product->final_price < $product->price && $product->price > 0)
                    @php
                        $discountPercent = round((($product->price - $product->final_price) / $product->price) * 100);
                    @endphp
                    <div class="position-absolute badge-discount text-white fw-bold d-flex align-items-center justify-content-center" 
                         style="top: 20px; left: 20px; width: 45px; height: 45px; border-radius: 50%; font-size: 0.85rem;">
                        {{ $discountPercent }}%
                    </div>
                @endif
            </div>

            <!-- Thumbnails -->
            <div class="d-flex gap-2 overflow-auto py-2">
                <!-- Main Image as first thumb -->
                <div class="thumb-box active" onclick="changeImage('{{ asset('storage/' . $product->image) }}', this)">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="thumb" style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px;" onerror="this.onerror=null; this.src='https://via.placeholder.com/60?text=Img';">
                </div>
                
                @if($product->images && $product->images->count() > 0)
                    @foreach($product->images as $img)
                        <div class="thumb-box" onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this)">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="thumb" style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px;" onerror="this.onerror=null; this.src='https://via.placeholder.com/60?text=Img';">
                        </div>
                    @endforeach
                @else
                    <!-- Mock thumbnails for demonstration -->
                    <div class="thumb-box" onclick="changeImage('{{ asset('storage/' . $product->image) }}', this)"><img src="{{ asset('storage/' . $product->image) }}" alt="thumb" style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px; filter: grayscale(50%);" onerror="this.onerror=null; this.src='https://via.placeholder.com/60?text=Img';"></div>
                    <div class="thumb-box" onclick="changeImage('{{ asset('storage/' . $product->image) }}', this)"><img src="{{ asset('storage/' . $product->image) }}" alt="thumb" style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px; filter: sepia(50%);" onerror="this.onerror=null; this.src='https://via.placeholder.com/60?text=Img';"></div>
                    <div class="thumb-box" onclick="changeImage('{{ asset('storage/' . $product->image) }}', this)"><img src="{{ asset('storage/' . $product->image) }}" alt="thumb" style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px; filter: invert(20%);" onerror="this.onerror=null; this.src='https://via.placeholder.com/60?text=Img';"></div>
                @endif
            </div>
        </div>

        <!-- 2. MIDDLE COLUMN: PRODUCT DETAILS -->
        <div class="col-lg-5 mb-4 px-lg-4">
            <span class="badge text-dark mb-2" style="background-color: #fce4ec; font-size: 0.7rem; padding: 5px 10px; letter-spacing: 0.5px;">ONLINE DEALS</span>
            
            <p class="fw-bold mb-1 text-uppercase text-dark" style="font-size: 1rem;">{{ $product->brand->name ?? 'COSRX' }}</p>
            <h1 class="mb-2" style="font-size: 1.5rem; font-weight: 500; color: #333;">{{ $product->name }}</h1>
            
            <div class="d-flex align-items-center mb-3">
                <div class="text-pink me-2 d-flex">
                    @for($i=0; $i<5; $i++)
                        <svg width="14" height="14" fill="#E91E63" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                    @endfor
                </div>
                <span class="fw-bold me-2" style="font-size: 0.95rem;">{{ number_format($product->rating, 1) }}</span>
                <span class="text-muted" style="font-size: 0.85rem;">28,930 orang merekomendasikan. <a href="#reviews-section" class="text-pink text-decoration-underline">See review</a></span>
            </div>
            
            <div class="mb-3 d-flex align-items-center">
                @if($product->final_price < $product->price)
                    <h2 class="text-price fw-bold mb-0 me-3" id="mainPriceDisplay">Rp{{ number_format($product->final_price, 0, ',', '.') }}</h2>
                    <span class="text-muted text-decoration-line-through me-2" id="originalPriceDisplay" style="font-size: 0.95rem;">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    @php $discountPercent = round((($product->price - $product->final_price) / $product->price) * 100); @endphp
                    <span class="badge text-price" style="background-color: #fce4ec; font-size: 0.75rem;">-{{ $discountPercent }}%</span>
                    <input type="hidden" id="itemPrice" value="{{ $product->final_price }}">
                    <input type="hidden" id="itemOriginalPrice" value="{{ $product->price }}">
                @else
                    <h2 class="text-price fw-bold mb-0" id="mainPriceDisplay">Rp{{ number_format($product->price, 0, ',', '.') }}</h2>
                    <input type="hidden" id="itemPrice" value="{{ $product->price }}">
                    <input type="hidden" id="itemOriginalPrice" value="{{ $product->price }}">
                @endif
            </div>

            <p class="text-muted mb-4" style="font-size: 0.85rem;">Nomor Izin Edar : NA26181202986</p>
            
            <div class="mb-4">
                <p class="fw-bold mb-2" style="font-size: 0.9rem;">Size</p>
                <div class="d-flex gap-2 flex-wrap">
                    @if($product->variants && $product->variants->count() > 0)
                        @foreach($product->variants as $index => $variant)
                            <button type="button" class="btn btn-variant {{ $index == 0 ? 'active' : '' }}" 
                                onclick="selectVariant(this)"
                                data-name="{{ $variant->name }}"
                                data-price="{{ $variant->price_modifier ?? 0 }}"
                                data-image="{{ $variant->image_path ? asset('storage/' . $variant->image_path) : '' }}"
                                data-desc="{{ $variant->description ?? '' }}"
                                data-use="{{ $variant->how_to_use ?? '' }}"
                                data-ing="{{ $variant->ingredients ?? '' }}"
                            >{{ $variant->name }}</button>
                        @endforeach
                    @else
                        <!-- Mock Variants -->
                        <button type="button" class="btn btn-variant" onclick="selectVariant(this)" data-name="150 ml" data-price="0">150 ml</button>
                        <button type="button" class="btn btn-variant active" onclick="selectVariant(this)" data-name="50 ml" data-price="0">50 ml</button>
                    @endif
                </div>
            </div>

            <!-- In-store pickup banner -->
            <div class="p-3 mb-4 rounded" style="background-color: #fff0f5; border: 1px dashed #ffb3c6;">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-store text-pink fs-4 me-3"></i>
                        <span class="fw-bold text-dark">Ambil di Toko (In-store Pickup)</span>
                    </div>
                </div>
                <p class="text-muted mb-1 ms-5" style="font-size: 0.85rem;">Tersedia untuk pengambilan langsung di <strong>Riana Collection</strong>.<br>Jl. Gatot Subroto Bengkalis (Dekat Momoyo Bengkalis).</p>
            </div>

            <!-- Bundles banner -->
            <div class="border rounded p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-dark">Tersedia dalam product bundle!</span>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-light p-1 px-2 border"><i class="fas fa-chevron-left" style="font-size: 0.7rem;"></i></button>
                        <button class="btn btn-sm btn-light p-1 px-2 border"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i></button>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="bundle1" style="width: 40px; height: 40px; object-fit: contain;">
                        <div class="ms-2">
                            <p class="mb-0 text-dark" style="font-size: 0.75rem; line-height: 1.2;">Total Acne Care - The<br>Niacinamide 15 Serum...</p>
                            <p class="text-price fw-bold mb-0" style="font-size: 0.8rem;">Rp424.000</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="bundle2" style="width: 40px; height: 40px; object-fit: contain; filter: hue-rotate(90deg);">
                        <div class="ms-2">
                            <p class="mb-0 text-dark" style="font-size: 0.75rem; line-height: 1.2;">Plump and Hydrated Skin<br>Bundle</p>
                            <p class="text-price fw-bold mb-0" style="font-size: 0.8rem;">Rp424.000</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expert Review Banner -->
            <div class="p-3 mb-4 rounded d-flex align-items-center" style="background-color: #fff0f5;">
                <i class="fas fa-check-circle text-dark fs-5 me-3"></i>
                <div>
                    <span class="fw-bold text-price d-block">Baca review Expert dulu, yuk!</span>
                    <span class="text-dark" style="font-size: 0.8rem;">Baca ulasan Expert untuk rekomendasi tepat! <a href="#" class="text-price fw-bold text-decoration-none">Baca di sini</a></span>
                </div>
            </div>

            <!-- TABS -->
            <ul class="nav nav-tabs custom-tabs" id="productTabs" role="tablist">
                <li class="nav-item flex-grow-1 text-center"><button class="nav-link w-100 active" data-bs-toggle="tab" data-bs-target="#desc">Deskripsi</button></li>
                <li class="nav-item flex-grow-1 text-center"><button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#how">Cara Pakai</button></li>
                <li class="nav-item flex-grow-1 text-center"><button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#ingredients">Kandungan</button></li>
            </ul>
            <div class="tab-content py-4 text-muted" style="font-size: 0.9rem; line-height: 1.6;">
                <div class="tab-pane fade show active" id="desc">
                    {!! nl2br(e($product->description ?? 'Deskripsi tidak tersedia.')) !!}
                    <br><br>Pembersih wajah dengan formula lembut yang bagus digunakan pagi hari...
                </div>
                <div class="tab-pane fade" id="how">{!! nl2br(e($product->how_to_use ?? 'Informasi cara pakai belum tersedia.')) !!}</div>
                <div class="tab-pane fade" id="ingredients">{!! nl2br(e($product->ingredients ?? 'Informasi kandungan belum tersedia.')) !!}</div>
            </div>
        </div>

        <!-- 3. RIGHT COLUMN: ACTION BOX -->
        <div class="col-lg-3">
            <div class="position-sticky top-0" style="padding-top: 20px; z-index: 10;">
                <div class="card border rounded-4 mb-3 shadow-sm" style="border-color: #eee !important;">
                    <div class="card-body p-4">
                        <form action="{{ route('cart.store') }}" method="POST" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant" id="selectedVariantInput" value="{{ $product->variants && $product->variants->count() > 0 ? $product->variants[0]->name : '50 ml' }}">
                            
                            <p class="fw-bold mb-2 text-dark" style="font-size: 0.9rem;">Jumlah</p>
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center border rounded me-3" style="width: 100px;">
                                    <button type="button" class="btn btn-sm btn-link text-muted text-decoration-none px-3 w-100" onclick="updateQty(-1)">-</button>
                                    <input type="text" name="qty" id="qtyInput" value="1" class="form-control form-control-sm border-0 text-center fw-bold p-0 bg-white" readonly>
                                    <button type="button" class="btn btn-sm btn-link text-price text-decoration-none px-3 w-100 fw-bold" onclick="updateQty(1)">+</button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <span class="badge border text-dark fw-normal px-3 py-2" id="selectedVariantLabel" style="font-size: 0.8rem;">
                                    {{ $product->variants && $product->variants->count() > 0 ? $product->variants[0]->name : '50 ml' }}
                                </span>
                            </div>
                            
                            <h3 class="fw-bold mb-4 text-dark" id="totalPriceDisplay">Rp0</h3>
                            
                            <button type="submit" class="btn btn-pink w-100 mb-2 py-2 fw-bold text-white shadow-sm" style="border-radius: 4px;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-bag me-2"></i> {{ $product->stock <= 0 ? 'Stok Habis' : 'Masukkan keranjang' }}
                            </button>
                            
                            <button type="submit" formaction="{{ route('checkout.index') }}" formmethod="GET" class="btn w-100 mb-3 py-2 fw-bold bg-white text-dark" style="border: 1px solid #333; border-radius: 4px;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                Beli sekarang
                            </button>
                            
                            <div class="d-flex justify-content-between align-items-center px-2 pt-2 border-top">
                                <a href="https://wa.me/6285274316395?text=Halo%20saya%20tertarik%20dengan%20produk%20{{ rawurlencode($product->name) }}" target="_blank" class="text-muted text-decoration-none d-flex align-items-center" style="font-size: 0.8rem;">
                                    <i class="far fa-comment-dots me-1"></i> Chat
                                </a>
                                @php $inWishlist = Auth::check() && Auth::user()->wishlists()->where('product_id', $product->id)->exists(); @endphp
                                <a href="javascript:void(0)" onclick="toggleWishlist(this, {{ $product->id }})" class="{{ $inWishlist ? 'text-danger' : 'text-muted' }} text-decoration-none d-flex align-items-center" style="font-size: 0.8rem;">
                                    <i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart me-1" id="wishlistIcon"></i> <span id="wishlistText">{{ $inWishlist ? 'Tersimpan' : 'Wishlist' }}</span>
                                </a>
                                <a href="javascript:void(0)" onclick="shareProduct()" class="text-muted text-decoration-none d-flex align-items-center" style="font-size: 0.8rem;">
                                    <i class="fas fa-share-alt me-1"></i> Bagikan
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Promo Spesial Widget -->
                <div class="card border rounded-4 shadow-sm" style="border-color: #eee !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">Promo Spesial</span>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm text-muted p-0"><i class="fas fa-chevron-left" style="font-size: 0.7rem;"></i></button>
                                <button class="btn btn-sm text-dark p-0"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i></button>
                            </div>
                        </div>
                        <div class="d-flex gap-2 overflow-auto pb-1" style="scrollbar-width: none;">
                            <div class="flex-shrink-0 p-2 rounded" style="background-color: #fff0f5; width: 140px; border-left: 3px solid #E91E63;">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-dark" style="font-size: 0.75rem; line-height: 1.2;">PRETTY GIFTS FOR YOU</span>
                                    <span class="text-pink fw-bold" style="font-size: 0.7rem;">%</span>
                                </div>
                                <span class="text-muted d-block mt-1" style="font-size: 0.65rem;">Voucher Discount</span>
                            </div>
                            <div class="flex-shrink-0 p-2 rounded" style="background-color: #f8f9fa; width: 140px; border-left: 3px solid #ccc;">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-dark" style="font-size: 0.75rem; line-height: 1.2;">DAILY 50K</span>
                                    <span class="text-secondary fw-bold" style="font-size: 0.7rem;">%</span>
                                </div>
                                <span class="text-muted d-block mt-1" style="font-size: 0.65rem;">Voucher</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="row mt-5" id="reviews-section">
        <div class="col-12">
            <h4 class="fw-bold mb-4">Ulasan Pembeli</h4>

            @auth
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background-color: #fff0f5;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Tulis Ulasan Anda</h6>
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Rating (Bintang)</label>
                            <select name="rating" class="form-select w-25" required>
                                <option value="5">⭐⭐⭐⭐⭐ Sangat Baik</option>
                                <option value="4">⭐⭐⭐⭐ Baik</option>
                                <option value="3">⭐⭐⭐ Cukup</option>
                                <option value="2">⭐⭐ Kurang</option>
                                <option value="1">⭐ Sangat Kurang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Komentar</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Bagaimana pengalaman Anda menggunakan produk ini?" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm text-white fw-bold">Kirim Ulasan</button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-light border shadow-sm mb-4">
                <i class="fas fa-info-circle me-2 text-pink"></i> Silakan <a href="{{ route('login') }}" class="text-pink fw-bold text-decoration-none">login</a> terlebih dahulu untuk meninggalkan ulasan.
            </div>
            @endauth
            
            @if(isset($reviews) && $reviews->count() > 0)
                <div class="row">
                    <div class="col-12">
                        @foreach($reviews as $review)
                            <div class="card mb-4 shadow-sm border-0 rounded-4" style="background-color: #fcfcfc;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center text-white fw-bold me-3" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                            {{ substr($review->reviewer_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold text-dark" style="font-size: 1.05rem;">{{ $review->reviewer_name }}</p>
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="text-pink me-2 d-flex">
                                                    @for($i=0; $i<5; $i++)
                                                        @if($i < floor($review->rating))
                                                            <svg width="14" height="14" fill="#E91E63" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                                        @else
                                                            <svg width="14" height="14" fill="#e0e0e0" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-muted" style="font-size: 0.85rem;"><i class="far fa-clock me-1"></i>{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-dark" style="font-size: 1rem; line-height: 1.6;">{{ $review->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-4 d-flex justify-content-center custom-pagination">
                            {{ $reviews->fragment('reviews-section')->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @else
                <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
            @endif
        </div>
    </div>
</div>

<script>
    let basePrice = document.getElementById('itemPrice').value;
    let variantPriceModifier = 0;
    
    function changeImage(src, element) {
        document.getElementById('mainImage').src = src;
        // Update active class on thumbnails
        document.querySelectorAll('.thumb-box').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    function selectVariant(element) {
        // Update visual active state
        document.querySelectorAll('.btn-variant').forEach(btn => btn.classList.remove('active'));
        element.classList.add('active');
        
        // Read data attributes
        let variantName = element.getAttribute('data-name');
        let modifier = element.getAttribute('data-price') || 0;
        let image = element.getAttribute('data-image');
        let desc = element.getAttribute('data-desc');
        let use = element.getAttribute('data-use');
        let ing = element.getAttribute('data-ing');
        
        // Update logic
        document.getElementById('selectedVariantInput').value = variantName;
        document.getElementById('selectedVariantLabel').innerText = variantName;
        variantPriceModifier = parseFloat(modifier);
        
        // Update Main Image
        if(image) {
            document.getElementById('mainImage').src = image;
            document.querySelectorAll('.thumb-box').forEach(el => el.classList.remove('active'));
        }
        
        // Update Tabs Text
        if(desc) document.getElementById('desc').innerHTML = desc.replace(/\n/g, '<br>');
        if(use) document.getElementById('how').innerHTML = use.replace(/\n/g, '<br>');
        if(ing) document.getElementById('ingredients').innerHTML = ing.replace(/\n/g, '<br>');
        
        updateTotalPrice();
    }

    function updateQty(change) {
        let input = document.getElementById('qtyInput');
        let currentQty = parseInt(input.value);
        let maxQty = {{ $product->stock }};
        
        let newQty = currentQty + change;
        if(newQty >= 1 && newQty <= maxQty) {
            input.value = newQty;
            updateTotalPrice();
        }
    }

    function updateTotalPrice() {
        let qty = parseInt(document.getElementById('qtyInput').value);
        let currentItemPrice = variantPriceModifier > 0 ? parseFloat(variantPriceModifier) : parseFloat(basePrice);
        let total = currentItemPrice * qty;
        
        // Format to Rupiah
        let formattedTotal = new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('totalPriceDisplay').innerText = 'Rp' + formattedTotal;

        // Update main price display under the title
        let formattedCurrent = new Intl.NumberFormat('id-ID').format(currentItemPrice);
        document.getElementById('mainPriceDisplay').innerText = 'Rp' + formattedCurrent;

        // Update original price if it exists (for flash sale)
        let originalPriceEl = document.getElementById('originalPriceDisplay');
        if(originalPriceEl) {
            let baseOriginal = parseFloat(document.getElementById('itemOriginalPrice').value);
            let currentOriginal = variantPriceModifier > 0 ? parseFloat(variantPriceModifier) : baseOriginal;
            originalPriceEl.innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(currentOriginal);
        }
    }

    // Initialize price on load
    window.onload = function() {
        // Find active variant if any
        let activeVariant = document.querySelector('.btn-variant.active');
        if (activeVariant) {
            let modifier = activeVariant.getAttribute('data-price') || 0;
            variantPriceModifier = parseFloat(modifier);
        }
        updateTotalPrice();
    };

    function toggleWishlist(element, productId) {
        @guest
            alert('Silakan login terlebih dahulu untuk menyimpan wishlist.');
            window.location.href = "{{ route('login') }}";
            return;
        @endguest

        let icon = document.getElementById('wishlistIcon');
        let text = document.getElementById('wishlistText');

        fetch("{{ route('wishlist.toggle') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'added') {
                icon.classList.remove('far');
                icon.classList.add('fas');
                element.classList.remove('text-muted');
                element.classList.add('text-danger');
                text.innerText = 'Tersimpan';
            } else if (data.status === 'removed') {
                icon.classList.remove('fas');
                icon.classList.add('far');
                element.classList.remove('text-danger');
                element.classList.add('text-muted');
                text.innerText = 'Wishlist';
            }
        }).catch(err => {
            console.error('Error toggling wishlist', err);
            alert('Terjadi kesalahan, coba lagi nanti.');
        });
    }

    function shareProduct() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $product->name }}',
                url: window.location.href
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert("Link produk berhasil disalin!");
        }
    }
</script>

<style>
    .text-pink { color: #FF6699; }
    .text-price { color: #E91E63; }
    .badge-discount { background-color: #E91E63; }
    .btn-pink { background-color: #E91E63; border-color: #E91E63; transition: 0.3s; }
    .btn-pink:hover { background-color: #c2185b; border-color: #c2185b; color: white; }
    
    /* Thumbnails */
    .thumb-box { width: 65px; height: 65px; border: 2px solid #eee; border-radius: 8px; padding: 2px; cursor: pointer; transition: 0.2s; opacity: 0.6; background: #fff; }
    .thumb-box:hover { opacity: 1; border-color: #ffb3c6; }
    .thumb-box.active { border-color: #E91E63; opacity: 1; }
    
    /* Variant Buttons */
    .btn-variant { border: 1px solid #ccc; color: #333; background: white; border-radius: 4px; padding: 4px 15px; font-size: 0.85rem; font-weight: 500; transition: 0.2s; }
    .btn-variant:hover { border-color: #FF6699; color: #FF6699; }
    .btn-variant.active { border-color: #E91E63; color: #E91E63; background-color: #fff0f5; }

    /* Custom Tabs */
    .custom-tabs { border-bottom: 2px solid #eee; }
    .custom-tabs .nav-link { border: none; color: #777; font-weight: bold; background: transparent; border-bottom: 2px solid transparent; border-radius: 0; padding-bottom: 12px; }
    .custom-tabs .nav-link:hover { color: #E91E63; }
    .custom-tabs .nav-link.active { color: #E91E63; border-bottom: 2px solid #E91E63; }
    
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
</style>
@endsection