<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaselineSubmitterRequest extends FormRequest
{
    /**
     * Otorisasi dilakukan di Middleware (Sanctum/Auth),
     * jadi biarkan return true.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Identitas Institusi & PIC
            'nama_institusi'  => 'sometimes|string|max:255',
            'jenis_institusi' => 'sometimes|in:PTN,PTS,PTK',
            'nama_pic'        => 'sometimes|string|max:255',
            'jabatan_pic'     => 'sometimes|string|max:255',
            'no_hp_pic'       => 'sometimes|string|max:20',

            // Parameter Kuantitatif
            'jml_mahasiswa'   => 'sometimes|integer|min:0',
            'jml_dosen'       => 'sometimes|integer|min:0',
            'jml_tendik'      => 'sometimes|integer|min:0',
            'jml_prodi'       => 'sometimes|integer|min:0',
            'jml_ukm'         => 'sometimes|integer|min:0',
            'jml_fakultas'    => 'sometimes|integer|min:0',

            // Teks Kualitatif
            'visi'            => 'sometimes|string',
            'misi'            => 'sometimes|string',

            // Parameter Agama (Array)
            'agama'           => 'sometimes|array',
            'agama.*'         => 'integer|min:0', // Memastikan isi value array-nya (misal: 'islam' => 1500) adalah angka positif

            // Parameter Dokumen Legal
            'legal_documents'                         => 'sometimes|array',
            'legal_documents.surat_pengantar'         => 'sometimes|file|mimes:pdf|max:2048',
            'legal_documents.sk_pendirian_pt'         => 'sometimes|file|mimes:pdf|max:2048',
            'legal_documents.sk_rektor_bela_negara'   => 'sometimes|file|mimes:pdf|max:2048',
            'legal_documents.struktur_organisasi'     => 'sometimes|file|mimes:pdf|max:2048',
            'legal_documents.pakta_integritas'        => 'sometimes|file|mimes:pdf|max:2048',
            'legal_documents.dokumen_akreditasi'      => 'sometimes|file|mimes:pdf|max:2048',
            'legal_documents.surat_pernyataan_pic'    => 'sometimes|file|mimes:pdf|max:2048',
        ];
    }

    /**
     * (Opsional) Custom pesan error jika diperlukan
     */
    public function messages(): array
    {
        return [
            'agama.*.integer' => 'Jumlah penganut agama harus berupa angka.',
            'legal_documents.*.mimes' => 'Semua dokumen legal harus berformat PDF.',
            'legal_documents.*.max' => 'Ukuran maksimal setiap dokumen adalah 2MB.',
        ];
    }
}