<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ass = \App\Models\Assessment::first();
$svc = app(\App\Services\AssessmentService::class);
try {
    $svc->getAllQuestionsWithAnswers($ass);
    echo "SUCCESS\n";
} catch(\Throwable $e) {
    echo $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
}
