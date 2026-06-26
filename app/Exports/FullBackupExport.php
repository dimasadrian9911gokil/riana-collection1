<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Backup\OrdersExport;
use App\Exports\Backup\ProductsExport;
use App\Exports\Backup\UsersExport;
use App\Exports\Backup\CategoriesExport;
use App\Exports\Backup\BrandsExport;
use App\Exports\Backup\FlashSalesExport;
use App\Exports\Backup\VouchersExport;

class FullBackupExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new OrdersExport(),
            new ProductsExport(),
            new UsersExport(),
            new CategoriesExport(),
            new BrandsExport(),
            new FlashSalesExport(),
            new VouchersExport(),
        ];
    }
}
