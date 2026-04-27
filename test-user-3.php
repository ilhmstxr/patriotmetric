<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
// Set the Auth::id() to 3 by logging in!
Illuminate\Support\Facades\Auth::loginUsingId(3);

$request = Illuminate\Http\Request::create('/api/assessment/reviewer/tasks', 'GET');
$response = $kernel->handle($request);
echo $response->getContent();
