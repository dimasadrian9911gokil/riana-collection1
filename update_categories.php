<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desc = [
    'makeup' => 'Koleksi lengkap produk tata rias wajah untuk mempercantik penampilan sehari-hari hingga acara spesial, mulai dari foundation, lipstik, hingga eyeshadow.', 
    'skincare' => 'Rangkaian produk perawatan kulit wajah untuk menjaga kesehatan, mencerahkan, dan mengatasi masalah kulit seperti jerawat atau kusam.', 
    'parfum' => 'Pilihan wewangian premium dengan aroma tahan lama untuk meningkatkan rasa percaya diri dan meninggalkan kesan tak terlupakan.', 
    'sunscreen' => 'Produk pelindung kulit dari paparan sinar UV matahari yang berbahaya, mencegah penuaan dini dan kulit terbakar.', 
    'body-care' => 'Perawatan tubuh menyeluruh mulai dari sabun mandi, lotion, hingga lulur untuk kulit tubuh yang lembut, sehat, dan wangi.', 
    'gift-set' => 'Paket hadiah eksklusif yang berisi kumpulan produk kecantikan pilihan, cocok diberikan untuk orang tersayang pada momen spesial.'
];

foreach(\App\Models\Category::all() as $cat) { 
    if(isset($desc[$cat->slug])) { 
        $cat->description = $desc[$cat->slug]; 
        $cat->save(); 
    } 
}
echo "Descriptions updated successfully!\n";
