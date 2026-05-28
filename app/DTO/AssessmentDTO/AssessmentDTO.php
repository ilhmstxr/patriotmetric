<?php

namespace App\DTO\AssessmentDTO;

use App\DTO\BaseDTO;

class AssessmentDTO extends BaseDTO
{
    public int $userId;
    public ?int $reviewerId;
    public string $institutionId;
    public int $tahunPeriode;
    public string $status;
    public string $namaPic;
    public ?string $jabatanPic;
    public string $noHpPic;
    public ?array $skorRekapJson;

    public function __construct($data)
    {
        if (is_numeric($data)) {
            $this->userId = (int) $data;
            $this->reviewerId = null;
            $this->institutionId = '';
            $this->tahunPeriode = date('Y');
            $this->status = 'ACTIVE';
            $this->namaPic = '';
            $this->jabatanPic = null;
            $this->noHpPic = '';
            $this->skorRekapJson = null;
        } else {
            $data = (array) $data;
            $this->userId = $data['user_id'] ?? $data['userId'] ?? 0;
            $this->reviewerId = $data['reviewer_id'] ?? $data['reviewerId'] ?? null;
            $this->institutionId = $data['institution_id'] ?? $data['institutionId'] ?? '';
            $this->tahunPeriode = $data['tahun_periode'] ?? $data['tahunPeriode'] ?? date('Y');
            $this->status = $data['status'] ?? $data['status'] ?? 'ACTIVE';
            $this->namaPic = $data['nama_pic'] ?? $data['namaPic'] ?? '';
            $this->jabatanPic = $data['jabatan_pic'] ?? $data['jabatanPic'] ?? null;
            $this->noHpPic = $data['no_hp_pic'] ?? $data['noHpPic'] ?? '';
            $this->skorRekapJson = $data['skor_rekap_json'] ?? $data['skorRekapJson'] ?? null;
        }
    }
}
