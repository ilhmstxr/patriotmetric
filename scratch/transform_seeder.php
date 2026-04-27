<?php
$filePath = 'c:\\Users\\Ilhamstxr\\Documents\\laragon\\www\\patriotmetric\\database\\seeders\\PertanyaanSeeder.php';
$content = file_get_contents($filePath);

// Regex to find 'kebutuhan_bukti' => '...',
$content = preg_replace_callback("/'kebutuhan_bukti' => '(.*?)',/", function($matches) {
    $items = array_map('trim', explode(',', $matches[1]));
    $arrayStr = "['" . implode("', '", $items) . "']";
    return "'kebutuhan_bukti' => $arrayStr,";
}, $content);

file_put_contents($filePath, $content);
echo "Transformation complete.\n";
