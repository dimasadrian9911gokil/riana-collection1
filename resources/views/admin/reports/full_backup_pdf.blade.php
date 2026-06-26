<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Full System Backup - Riana Collection</title>
    <style>
        @page { margin: 30px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #444; line-height: 1.4; background-color: #ffffff; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #FF6699; padding-bottom: 10px; }
        .company-name { font-size: 24px; font-weight: bold; color: #E91E63; margin: 0; letter-spacing: 1px; }
        .report-title { font-size: 16px; font-weight: bold; color: #333; margin: 5px 0; text-transform: uppercase; }
        .report-date { font-size: 10px; color: #777; }

        .section-title { font-size: 14px; font-weight: bold; color: #E91E63; border-bottom: 1px solid #ffccd5; padding-bottom: 5px; margin-top: 30px; margin-bottom: 10px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { 
            border-bottom: 1px solid #eeeeee; 
            padding: 8px 5px; 
            text-align: left; 
            vertical-align: middle;
        }
        .data-table th { 
            background-color: #FF6699; 
            color: white; 
            font-size: 9px; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            font-weight: bold;
        }
        .data-table tbody tr:nth-child(even) { background-color: #fafafa; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="company-name">RIANA COLLECTION</h1>
        <div class="report-title">FULL SYSTEM BACKUP DATA</div>
        <div class="report-date">Waktu Backup: {{ date('d F Y, H:i:s') }}</div>
    </div>

    <!-- 1. DATA PRODUK -->
    <div class="section-title">A. Data Produk (Total: {{ $products->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="30%">Nama Produk</th>
                <th width="15%">Kategori</th>
                <th width="15%">Brand</th>
                <th width="15%" class="text-right">Harga</th>
                <th width="10%" class="text-center">Stok</th>
                <th width="10%" class="text-center">Tgl Input</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td class="fw-bold">{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>{{ $product->brand->name ?? '-' }}</td>
                <td class="text-right">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="text-center">{{ $product->stock }}</td>
                <td class="text-center">{{ $product->created_at->format('d/m/y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- 2. DATA PELANGGAN -->
    <div class="section-title">B. Data Pelanggan (Total: {{ $users->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="30%">Nama Pengguna</th>
                <th width="35%">Email</th>
                <th width="15%" class="text-center">Peran (Role)</th>
                <th width="15%" class="text-center">Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td class="fw-bold">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">{{ strtoupper($user->getRoleNames()->first() ?? 'USER') }}</td>
                <td class="text-center">{{ $user->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- 3. DATA PESANAN -->
    <div class="section-title">C. Data Seluruh Pesanan (Total: {{ $orders->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Invoice</th>
                <th width="20%">Pelanggan</th>
                <th width="15%" class="text-center">Status</th>
                <th width="15%" class="text-center">Pembayaran</th>
                <th width="15%" class="text-right">Total (Rp)</th>
                <th width="15%" class="text-center">Waktu Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td class="fw-bold">{{ $order->invoice }}</td>
                <td>{{ $order->user->name ?? 'Terhapus' }}</td>
                <td class="text-center">{{ ucwords(str_replace('_', ' ', $order->status)) }}</td>
                <td class="text-center">{{ strtoupper($order->payment_method) }}</td>
                <td class="text-right fw-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                <td class="text-center">{{ $order->created_at->format('d/m/y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 4. DATA BRAND -->
    <div class="page-break"></div>
    <div class="section-title">D. Data Brand (Total: {{ $brands->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th width="40%">Nama Brand</th>
                <th width="30%">Slug</th>
                <th width="20%" class="text-center">Tgl Input</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td class="fw-bold">{{ $brand->name }}</td>
                <td>{{ $brand->slug }}</td>
                <td class="text-center">{{ $brand->created_at->format('d/m/y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 5. DATA FLASH SALE -->
    <div class="page-break"></div>
    <div class="section-title">E. Data Flash Sale & Diskon (Total: {{ $flashSales->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="20%">Nama Event</th>
                <th width="25%">Produk</th>
                <th width="15%" class="text-right">Harga Asli</th>
                <th width="15%" class="text-right">Harga Diskon</th>
                <th width="10%" class="text-center">Stok</th>
                <th width="10%" class="text-center">Status Event</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flashSales as $fs)
            <tr>
                <td>{{ $fs->id }}</td>
                <td>{{ $fs->flashSale->name ?? '-' }}</td>
                <td class="fw-bold">{{ $fs->product->name ?? '-' }}</td>
                <td class="text-right text-muted" style="text-decoration: line-through;">Rp{{ number_format($fs->product->price ?? 0, 0, ',', '.') }}</td>
                <td class="text-right fw-bold text-danger">Rp{{ number_format($fs->discount_price, 0, ',', '.') }}</td>
                <td class="text-center">{{ $fs->stock }}</td>
                <td class="text-center">{{ ($fs->flashSale && $fs->flashSale->is_active) ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 6. DATA VOUCHER -->
    <div class="page-break"></div>
    <div class="section-title">F. Data Voucher (Total: {{ $vouchers->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Kode Voucher</th>
                <th width="15%" class="text-center">Tipe</th>
                <th width="15%" class="text-right">Nominal</th>
                <th width="15%" class="text-right">Min. Belanja</th>
                <th width="10%" class="text-center">Sisa Kuota</th>
                <th width="15%" class="text-center">Masa Berlaku</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vouchers as $vc)
            <tr>
                <td>{{ $vc->id }}</td>
                <td class="fw-bold text-success">{{ $vc->code }}</td>
                <td class="text-center">{{ ucfirst($vc->discount_type) }}</td>
                <td class="text-right">
                    {{ $vc->discount_type == 'nominal' ? 'Rp'.number_format($vc->discount_amount,0,',','.') : $vc->discount_amount.'%' }}
                </td>
                <td class="text-right">Rp{{ number_format($vc->min_purchase, 0, ',', '.') }}</td>
                <td class="text-center">{{ $vc->quota - $vc->used }} (dari {{ $vc->quota }})</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($vc->valid_until)->format('d/m/y') }}</td>
                <td class="text-center">{{ $vc->is_active ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
