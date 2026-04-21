<?php

namespace App\DTO\SubmitterDTO;

class BaselineDTO
{
    public readonly int $userId;
    public readonly int $jmlMahasiswa;
    public readonly int $jmlDosen;
    public readonly int $jmlTendik;
    public readonly int $jmlProdi;
    public readonly int $jmlUkm;
    public readonly int $jmlAgama;
    public readonly string $visi;
    public readonly string $misi;
    public readonly array $legalDocuments;

    public function __construct(int $userId, array $validatedData)
    {
        $this->userId = $userId;
        
        // Mapping langsung dari array hasil FormRequest ke property DTO
        $this->jmlMahasiswa = $validatedData['jml_mahasiswa'];
        $this->jmlDosen = $validatedData['jml_dosen'];
        $this->jmlTendik = $validatedData['jml_tendik'];
        $this->jmlProdi = $validatedData['jml_prodi'];
        $this->jmlUkm = $validatedData['jml_ukm'];
        $this->jmlAgama = $validatedData['jml_agama'];
        $this->visi = $validatedData['visi'];
        $this->misi = $validatedData['misi'];
        
        // Array dokumen
        $this->legalDocuments = $validatedData['legal_documents'];
    }
}
