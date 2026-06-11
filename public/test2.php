<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/checkout?product_id=1&qty=1&variant=Cerah+Halus', 'GET');
$response = $kernel->handle($request);

echo $response->getContent();
