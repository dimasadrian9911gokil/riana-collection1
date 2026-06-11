<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('products') }}" method="GET">
            <h5 class="fw-bold mb-3">Filter Produk</h5>
            
            <input type="text" name="search" class="form-control mb-4" 
                   placeholder="Cari produk..." value="{{ request('search') }}">

            <h6 class="fw-bold">Kategori</h6>
            @foreach(\App\Models\Category::all() as $category)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="category[]" 
                       value="{{ $category->slug }}" id="cat{{ $category->id }}"
                       {{ in_array($category->slug, (array)request('category')) ? 'checked' : '' }}>
                <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
            </div>
            @endforeach

            <hr>

            <h6 class="fw-bold">Brand</h6>
            @foreach(['Skintific','Somethinc','Wardah','Scarlett','Avoskin','Azarine'] as $brand)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="brand[]" 
                       value="{{ strtolower($brand) }}" 
                       {{ in_array(strtolower($brand), (array)request('brand')) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $brand }}</label>
            </div>
            @endforeach

            <hr>

            <h6 class="fw-bold">Tipe Kulit</h6>
            @foreach(['Kering','Berminyak','Sensitif','Kombinasi'] as $skin)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skin_type[]" 
                       value="{{ strtolower($skin) }}"
                       {{ in_array(strtolower($skin), (array)request('skin_type')) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $skin }}</label>
            </div>
            @endforeach

            <hr>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-danger">Terapkan Filter</button>
                <a href="{{ route('products') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>