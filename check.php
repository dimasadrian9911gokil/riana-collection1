<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\Product::find(7);
if($p) {
    echo "Product: {$p->name}\n";
    echo "Base Price: {$p->price}\n";
    foreach($p->variants as $v) {
        echo "Variant {$v->name} - Price Mod: {$v->price_modifier}\n";
    }
}
