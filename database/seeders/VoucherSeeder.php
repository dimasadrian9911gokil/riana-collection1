<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [
            ['code' => 'DISC5', 'name' => 'Diskon 5%', 'discount_type' => 'percentage', 'discount_amount' => 5, 'min_spend' => 50000],
            ['code' => 'DISC10', 'name' => 'Diskon 10%', 'discount_type' => 'percentage', 'discount_amount' => 10, 'min_spend' => 100000],
            ['code' => 'DISC15', 'name' => 'Diskon 15%', 'discount_type' => 'percentage', 'discount_amount' => 15, 'min_spend' => 150000],
            ['code' => 'DISC20', 'name' => 'Diskon 20%', 'discount_type' => 'percentage', 'discount_amount' => 20, 'min_spend' => 200000],
            ['code' => 'HEMAT10K', 'name' => 'Potongan Rp10.000', 'discount_type' => 'fixed', 'discount_amount' => 10000, 'min_spend' => 75000],
            ['code' => 'HEMAT20K', 'name' => 'Potongan Rp20.000', 'discount_type' => 'fixed', 'discount_amount' => 20000, 'min_spend' => 125000],
            ['code' => 'HEMAT30K', 'name' => 'Potongan Rp30.000', 'discount_type' => 'fixed', 'discount_amount' => 30000, 'min_spend' => 175000],
            ['code' => 'HEMAT50K', 'name' => 'Potongan Rp50.000', 'discount_type' => 'fixed', 'discount_amount' => 50000, 'min_spend' => 250000],
            ['code' => 'SUPER25', 'name' => 'Diskon Spesial 25%', 'discount_type' => 'percentage', 'discount_amount' => 25, 'min_spend' => 300000],
            ['code' => 'SUPER100K', 'name' => 'Potongan Rp100.000', 'discount_type' => 'fixed', 'discount_amount' => 100000, 'min_spend' => 500000],
        ];

        foreach ($vouchers as $voucher) {
            \App\Models\Voucher::create($voucher);
        }
    }
}
