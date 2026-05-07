<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use Illuminate\Support\Str;

$jsonPath = 'c:\Users\HP\Downloads\gemini-code-1778128118949.json';
$json = file_get_contents($jsonPath);
$data = json_decode($json, true);

$pertanyaans = Pertanyaan::all();

foreach ($data as $item) {
    $indikator = trim(strtolower($item['indikator_implementasi']));
    
    // Find matching pertanyaan
    $matchedPertanyaan = null;
    $highestSimilarity = 0;
    
    foreach ($pertanyaans as $p) {
        $teks = trim(strtolower($p->teks_pertanyaan));
        similar_text($indikator, $teks, $percent);
        
        if ($percent > $highestSimilarity) {
            $highestSimilarity = $percent;
            $matchedPertanyaan = $p;
        }
    }
    
    if ($matchedPertanyaan && $highestSimilarity > 70) { // 70% threshold
        echo "Matched [{$highestSimilarity}%]:\n JSON: {$indikator}\n DB:   " . $matchedPertanyaan->teks_pertanyaan . "\n";
        
        // Delete existing opsi to avoid duplicates
        OpsiJawaban::where('pertanyaan_id', $matchedPertanyaan->id)->delete();
        
        // Insert new ones
        foreach ($item['skor_bobot'] as $skor => $keterangan) {
            OpsiJawaban::create([
                'pertanyaan_id' => $matchedPertanyaan->id,
                'opsi_jawaban' => (string) $skor,
                'value' => (int) $skor, // Just set it to skor for display
                'keterangan' => $keterangan
            ]);
        }
        echo "--> Inserted options for Pertanyaan ID: {$matchedPertanyaan->id}\n\n";
    } else {
        echo "Failed to match: {$indikator} (Highest: {$highestSimilarity}%)\n\n";
    }
}
