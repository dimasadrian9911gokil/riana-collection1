<form action="{{ route('products') }}" method="GET" class="card p-3 shadow-sm border-0 rounded-4">
    <h6 class="fw-bold mt-2">Category</h6>
    <div class="mb-3">
        @foreach($categories as $category)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="category[]" 
                   value="{{ $category->slug }}" 
                   {{ in_array($category->slug, (array)request('category', [])) ? 'checked' : '' }}>
            <label class="form-check-label">{{ $category->name }}</label>
        </div>
        @endforeach
    </div>

    <hr>
    
    <h6 class="fw-bold">Filter Harga</h6>
    <input type="number" name="min_price" class="form-control mb-2" placeholder="Min" value="{{ request('min_price') }}">
    <input type="number" name="max_price" class="form-control mb-3" placeholder="Max" value="{{ request('max_price') }}">

    <h6 class="fw-bold">Rating</h6>
    @for($i = 5; $i >= 1; $i--)
    <div class="form-check">
        <input class="form-check-input" type="radio" name="rating" value="{{$i}}" {{ request('rating') == $i ? 'checked' : '' }}>
        <label class="form-check-label">{{$i}} Stars & Up</label>
    </div>
    @endfor

    <hr>
    
    <h6 class="fw-bold">Kondisi Kulit</h6>
    @foreach(['normal' => 'Normal', 'sensitif' => 'Kulit Sensitif'] as $value => $label)
    <div class="form-check">
        <input class="form-check-input" type="radio" name="skin_type" value="{{ $value }}" 
               {{ request('skin_type') == $value ? 'checked' : '' }}>
        <label class="form-check-label">{{ $label }}</label>
    </div>
    @endforeach

    <hr>
    
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="in_stock" value="1" {{ request('in_stock') == '1' ? 'checked' : '' }}>
        <label class="form-check-label">Show Available Stock</label>
    </div>

    <button type="submit" class="btn btn-pink w-100 rounded-pill text-white mb-2">Terapkan Filter</button>
    <a href="{{ route('products') }}" class="btn btn-outline-secondary w-100 rounded-pill">Reset</a>
</form>