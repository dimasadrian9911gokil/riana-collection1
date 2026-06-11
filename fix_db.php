<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\ProductVariant::where('price_modifier', '!=', 0)->update(['price_modifier' => 0]);
echo "Price modifiers reset successfully.\n";
