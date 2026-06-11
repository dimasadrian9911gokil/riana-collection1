<form action="{{ route('products') }}" method="GET" class="p-3 bg-white shadow-sm rounded">
    <h6 class="fw-bold">Category</h6>
    @foreach($categories as $category)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="category[]" value="{{$category->id}}" onchange="this.form.submit()">
            <label class="form-check-label">{{$category->name}}</label>
        </div>
    @endforeach

    <hr>
    <h6 class="fw-bold">Price</h6>
    <input type="range" class="form-range" min="0" max="1000000" name="max_price">

    <hr>
    <h6 class="fw-bold">Ratings</h6>
    @for($i = 5; $i >= 1; $i--)
        <div class="form-check">
            <input class="form-check-input" type="radio" name="rating" value="{{$i}}" onchange="this.form.submit()">
            <label class="form-check-label">
                @for($j=0; $j<$i; $j++) <i class="fas fa-star text-warning"></i> @endfor
            </label>
        </div>
    @endfor

    <hr>
    <h6 class="fw-bold">Product</h6>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="available_only" onchange="this.form.submit()">
        <label class="form-check-label">Show Available Stock Only</label>
    </div>
</form>