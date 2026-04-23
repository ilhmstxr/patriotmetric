<?php

namespace App\DTO\AssessmentDTO;

class BaselineDTO
{
    public readonly int $userId;
    public readonly ?string $namaInstitusi;
    public readonly ?string $jenisInstitusi;
    public readonly ?string $namaPic;
    public readonly ?string $jabatanPic;
    public readonly ?string $noHpPic;

    public readonly ?int $jmlMhs;
    public readonly ?int $jmlDosen;
    public readonly ?int $jmlTendik;
    public readonly ?int $jmlProdi;
    public readonly ?int $jmlUkm;
    public readonly ?int $jmlFakultas;

    public readonly ?string $visi;
    public readonly ?string $misi;
    public readonly array $legalDocuments;

    public readonly array $dataAgama;

    public function __construct(int $userId, array $validatedData)
    {
        $this->userId = $userId;
        $this->namaInstitusi = $validatedData['nama_institusi'] ?? null;
        $this->jenisInstitusi = $validatedData['jenis_institusi'] ?? null;
        $this->namaPic = $validatedData['nama_pic'] ?? null;
        $this->jabatanPic = $validatedData['jabatan_pic'] ?? null;
        $this->noHpPic = $validatedData['no_hp_pic'] ?? null;

        $this->jmlMhs = $validatedData['jml_mahasiswa'] ?? null;
        $this->jmlDosen = $validatedData['jml_dosen'] ?? null;
        $this->jmlTendik = $validatedData['jml_tendik'] ?? null;
        $this->jmlProdi = $validatedData['jml_prodi'] ?? null;
        $this->jmlUkm = $validatedData['jml_ukm'] ?? null;
        $this->jmlFakultas = $validatedData['jml_fakultas'] ?? null;
        
        $this->visi = $validatedData['visi'] ?? null;
        $this->misi = $validatedData['misi'] ?? null;
        $this->legalDocuments = $validatedData['legal_documents'] ?? [];

        $this->dataAgama = $validatedData['agama'] ?? [];
    }
}
