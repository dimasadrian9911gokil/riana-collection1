<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$c = new \App\Models\Cart(['product_id' => 1, 'qty' => 1, 'variant' => 'Test Variant']);
echo "VARIANT: " . $c->variant . "\n";
