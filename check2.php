<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$asses = \App\Models\Pengumpulan::all();
foreach($asses as $ass) {
    echo "ID: {$ass->id}, User ID: {$ass->user_id}, Status: {$ass->status}\n";
}
