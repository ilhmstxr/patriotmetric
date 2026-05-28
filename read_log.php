<?php
$log = file_get_contents('storage/logs/laravel.log');
preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] local\.ERROR:.*?\{main\}/s', $log, $matches);
if (!empty($matches[0])) {
    $count = count($matches[0]);
    for ($i = max(0, $count - 5); $i < $count; $i++) {
        echo "================== ERROR $i ==================\n";
        echo substr($matches[0][$i], 0, 800) . "\n\n";
    }
} else {
    echo "No matches found.";
}
