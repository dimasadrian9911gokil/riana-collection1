<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">

<div class="container">

<a class="navbar-brand fw-bold text-danger"
href="{{ route('home') }}">

RIANA COLLECTION

</a>

<button
class="navbar-toggler"
data-bs-toggle="collapse"
data-bs-target="#navbarMenu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse"
id="navbarMenu">

<ul class="navbar-nav me-auto">

<li class="nav-item">
<a class="nav-link"
href="{{ route('home') }}">
Home
</a>
</li>

<li class="nav-item">
<a class="nav-link text-danger fw-bold"
href="{{ route('flashsale') }}">
🔥 Flash Sale
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="{{ route('products') }}">
Produk
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="{{ route('categories') }}">
Kategori
</a>
</li>

</ul>

<form class="d-flex">

<input
class="form-control"
placeholder="Cari produk">

</form>

</div>

</div>

</nav>