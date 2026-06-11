<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = \App\Models\Product::all();
foreach($products as $product) {
    if($product->reviews()->count() > 0) {
        $avg = $product->reviews()->avg('rating');
        $product->update(['rating' => $avg]);
        echo "Updated {$product->name} rating to {$avg}\n";
    }
}
echo "Done.\n";
