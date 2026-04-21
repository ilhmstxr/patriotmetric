<?php

namespace App\Http\Requests; // Pastikan namespace sesuai standar Laravel

use Illuminate\Foundation\Http\FormRequest;

class BaselineSubmitterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pastikan ini true jika otorisasi di-handle di middleware
    }

    public function rules(): array
    {
        return [
            // Parameter kuantitatif
            'jml_mahasiswa' => 'required|integer|min:0', // Tambahkan min:0, jumlah tidak mungkin minus
            'jml_dosen'     => 'required|integer|min:0',
            'jml_tendik'    => 'required|integer|min:0',
            'jml_prodi'     => 'required|integer|min:0',
            'jml_ukm'       => 'required|integer|min:0',
            'jml_agama'       => 'required|integer|min:0',
            'visi'          => 'required|string',
            'misi'          => 'required|string',

            // Parameter dokumen (Struktur array sudah benar)
            'legal_documents'                         => 'required|array',
            'legal_documents.surat_pengantar'         => 'required|file|mimes:pdf|max:2048',
            'legal_documents.sk_pendirian_pt'         => 'required|file|mimes:pdf|max:2048',
            'legal_documents.sk_rektor_bela_negara'   => 'required|file|mimes:pdf|max:2048',
            'legal_documents.struktur_organisasi'     => 'required|file|mimes:pdf|max:2048',
            'legal_documents.pakta_integritas'        => 'required|file|mimes:pdf|max:2048',
            'legal_documents.dokumen_akreditasi'      => 'required|file|mimes:pdf|max:2048',
            'legal_documents.surat_pernyataan_pic'    => 'required|file|mimes:pdf|max:2048',
        ];
    }
}