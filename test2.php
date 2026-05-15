<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ass = \App\Models\Assessment::first();
$svc = app(\App\Services\AssessmentService::class);
try {
    $data = $svc->getAllQuestionsWithAnswers($ass);
    $json = json_encode($data);
    if ($json === false) {
        echo "JSON Error: " . json_last_error_msg() . "\n";
    } else {
        echo "JSON OK: " . strlen($json) . " bytes\n";
    }
} catch(\Throwable $e) {
    echo $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
}
