<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pertanyaans = DB::table('pertanyaans')->whereNotNull('kebutuhan_bukti')->get();

        foreach ($pertanyaans as $p) {
            $value = $p->kebutuhan_bukti;

            // Skip jika sudah HTML
            if (str_contains($value, '<')) {
                continue;
            }

            // Coba decode sebagai JSON array
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $html = '<ul>' . implode('', array_map(fn($item) => '<li>' . e($item) . '</li>', $decoded)) . '</ul>';
            } else {
                // Comma-separated string
                $items = array_filter(array_map('trim', explode(',', $value)));
                $html = '<ul>' . implode('', array_map(fn($item) => '<li>' . e($item) . '</li>', $items)) . '</ul>';
            }

            DB::table('pertanyaans')->where('id', $p->id)->update(['kebutuhan_bukti' => $html]);
        }
    }

    public function down(): void
    {
        // Not reversible
    }
};
