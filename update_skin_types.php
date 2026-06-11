<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$types = ['kering', 'berminyak', 'sensitif', 'kombinasi'];
\App\Models\Product::chunk(50, function ($products) use ($types) { 
    foreach ($products as $product) { 
        $product->skin_type = $types[array_rand($types)]; 
        $product->save(); 
    } 
}); 
echo "Products updated successfully!\n";
