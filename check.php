<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ass = \App\Models\Pengumpulan::where('user_id', 3)->first();
echo 'Assessment ID: ' . ($ass ? $ass->id : 'null') . ' Status: ' . ($ass ? $ass->status : 'null');
