<?php

$file = 'c:\laragon\www\patriotmetric\dokumen\penilaian.md';
$content = file_get_contents($file);

// Clean up merged lines
$content = preg_replace('/(\d{2,3}\.\s+Bukti)/', "\n$1", $content);

$lines = explode("\n", $content);

$categoryId = 1;
$questions = [];
$currentQ = null;

foreach ($lines as $line) {
    if (strpos($line, 'kategori index 0') !== false) {
        $categoryId = 1;
        continue;
    }
    if (strpos($line, 'kategori index 1') !== false) {
        $categoryId = 2;
        continue;
    }
    if (strpos($line, 'kategori index 2') !== false) {
        $categoryId = 3;
        continue;
    }

    if (preg_match('/^(\d+)\.\s+(.*)/', trim($line), $matches)) {
        if ($currentQ) {
            $questions[] = $currentQ;
        }
        $num = $matches[1];
        if ($num >= 16) {
            $teks = $matches[2];
            $tipe = 'text';
            if (stripos($teks, 'Pilihan Jawaban') !== false) {
                $tipe = 'pilihan_ganda';
                $teks = preg_replace('/\s*Pilihan Jawaban:?$/i', '', trim($teks));
            } elseif (stripos($teks, 'Link Drive') !== false) {
                $tipe = 'link_drive';
            } elseif (stripos($teks, 'angka') !== false) {
                $tipe = 'angka';
            }

            $currentQ = [
                'category_id' => $categoryId,
                'teks_pertanyaan' => trim($teks),
                'tipe' => $tipe,
                'opsi_jawaban' => null
            ];

            // Re-check for options on the same line if missed, mostly not the case
        }
    } elseif ($currentQ) {
        $lineTrimmed = trim($line);
        if (preg_match('/^\[(\d+)\]\s+(.*)/', $lineTrimmed, $m)) {
            $currentQ['tipe'] = 'pilihan_ganda';
            if (!is_array($currentQ['opsi_jawaban']))
                $currentQ['opsi_jawaban'] = [];
            $currentQ['opsi_jawaban'][] = [
                'urutan' => (int) $m[1],
                'teks_jawaban' => trim($m[2])
            ];
        } elseif (stripos($lineTrimmed, 'Input: (Masukkan Angka)') !== false) {
            $currentQ['tipe'] = 'angka';
        } elseif (stripos($lineTrimmed, 'Link Drive') !== false) {
            $currentQ['tipe'] = 'link_drive';
        }
    }
}
if ($currentQ) {
    $questions[] = $currentQ;
}

$export = "<?php\n\nnamespace Database\Seeders;\n\nuse App\Models\pertanyaan;\nuse Illuminate\Database\Seeder;\n\nclass PertanyaanSeeder extends Seeder\n{\n    public function run(): void\n    {\n        pertanyaan::truncate(); // optional, but let's just create\n        \$pertanyaan = [\n";

foreach ($questions as $q) {
    $export .= "            [\n";
    $export .= "                'category_id' => " . $q['category_id'] . ",\n";
    $export .= "                'teks_pertanyaan' => " . var_export($q['teks_pertanyaan'], true) . ",\n";
    $export .= "                'tipe' => " . var_export($q['tipe'], true) . ",\n";
    if ($q['opsi_jawaban'] !== null) {
        $export .= "                'opsi_jawaban' => [\n";
        foreach ($q['opsi_jawaban'] as $opt) {
            $export .= "                    ['urutan' => " . $opt['urutan'] . ", 'teks_jawaban' => " . var_export($opt['teks_jawaban'], true) . "],\n";
        }
        $export .= "                ],\n";
    } else {
        $export .= "                'opsi_jawaban' => null,\n";
    }
    $export .= "            ],\n";
}

$export .= "        ];\n\n";
$export .= "        foreach (\$pertanyaan as \$p) {\n";
$export .= "            pertanyaan::create(\$p);\n";
$export .= "        }\n";
$export .= "    }\n}\n";

file_put_contents('c:\laragon\www\patriotmetric\database\seeders\PertanyaanSeeder.php', $export);
echo "Done";
