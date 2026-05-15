<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$svc = app(\App\Services\AssessmentService::class);
$assessments = \App\Models\Assessment::all();
foreach($assessments as $ass) {
    try {
        $data = $svc->getAllQuestionsWithAnswers($ass);
        $json = json_encode($data);
        if ($json === false) {
            echo "Ass ID {$ass->id} JSON Error: " . json_last_error_msg() . "\n";
        } else {
            echo "Ass ID {$ass->id} OK\n";
        }
    } catch(\Throwable $e) {
        echo "Ass ID {$ass->id} ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
    }
}
