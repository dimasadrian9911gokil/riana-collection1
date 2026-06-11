<div class="container mt-5">

<div class="d-flex justify-content-between">

<h2 class="text-danger">

🔥 Flash Sale

</h2>

<a
href="{{ route('flashsale') }}"
class="btn btn-danger">

Lihat Semua

</a>

</div>

<div class="row mt-3">

@for($i=1;$i<=4;$i++)

<div class="col-md-3">

<div class="card">

<img
src="https://placehold.co/400x400"
class="card-img-top">

<div class="card-body">

<h6>
Produk Flash Sale
</h6>

<h5 class="text-danger">
Rp49.000
</h5>

</div>

</div>

</div>

@endfor

</div>

</div>