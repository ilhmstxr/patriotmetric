<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/api/assessment/peserta/questions/assessmentid=2', 'GET');
$response = $kernel->handle($request);
echo $response->getContent();
