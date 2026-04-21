<?php

namespace App\Services;

use App\Repositories\SubmitterRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use App\DTO\SubmitterDTO\SubmitterDTO;
use App\DTO\SubmitterDTO\BaselineDTO;
use Illuminate\Support\Str;

/**
 * @property \App\Repositories\SubmitterRepository $repository
 */
class SubmitterService extends BaseService
{
    public function __construct(SubmitterRepository $repository)
    {
        parent::__construct($repository);
    }


    public function validateAccess(SubmitterDTO $dto)
    {
        // 1. Interogasi Tabel User
        if (!$this->repository->isUserActive($dto->userId)) {
            throw new Exception("Akses Ditolak: Akun Anda berstatus tidak aktif atau ditangguhkan.", 403);
        }

        // 2. Interogasi Tabel Pengumpulan
        $assessment = $this->repository->findActiveByUserId($dto->userId);

        if (!$assessment) {
            throw new Exception("Akses Ditolak: Anda tidak memiliki sesi asesmen yang berstatus ACTIVE atau IN_PROGRESS.", 403);
        }

        // Jika lolos kedua syarat, kembalikan data assessment agar bisa dipakai 
        // oleh fungsi lain tanpa perlu query database lagi.
        return $assessment;
    }

    public function storeBaseline(BaselineDTO $dto)
    {
        // DONE
        $assessment = $this->repository->findActiveByUserId($dto->userId);

        $existingIdentitas = $this->repository->findIdentitasByPengumpulanId($assessment->id);

        if ($existingIdentitas) {
            throw new Exception("Data baseline (identitas) sudah pernah diisi untuk periode ini.", 403);
        }

        // 3. Proses File Upload (Eksekusi array legalDocuments dari DTO)
        // Anda wajib mengekstrak file dari DTO dan menyimpannya ke Storage (S3/Local)
        // Array kembaliannya berisi path file, contoh: ['surat_pengantar' => 'path/to/file.pdf']
        $documentPaths = $this->processLegalDocuments($dto->legalDocuments, $assessment->id, $assessment->tahun_periode);

        // 4. Transformasi DTO ke Array Database (Pola Service-Repository)
        $identitasData = [
            'pengumpulan_id' => $assessment->id,
            'jml_mahasiswa'  => $dto->jmlMahasiswa,
            'jml_dosen'      => $dto->jmlDosen,
            'jml_tendik'     => $dto->jmlTendik,
            'jml_prodi'      => $dto->jmlProdi,
            'jml_ukm'        => $dto->jmlUkm,
            'jml_agama'      => $dto->jmlAgama,
            'visi'           => $dto->visi,
            'misi'           => $dto->misi,
            // JSON Encode sangat penting jika DB kolomnya text/json
            'legal_documents' => json_encode($documentPaths),
            'is_verified'    => false, // Nilai default
        ];

        // 5. Eksekusi Simpan Data tanpa mengubah status pengumpulan
        return $this->repository->createIdentitas($identitasData);
    }

    private function processLegalDocuments(array $documents, $assessment, $year): array
    {
        $paths = [];

        // 1. Dapatkan nama instansi dan tahun (Sesuaikan pemanggilan relasi 'institution' 
        // dengan nama relasi di Model Pengumpulan Anda)
        // Jika tidak ada relasi, ambil dari DB berdasarkan $assessment->institution_id
        $namaInstansi = $assessment->institusi->name ?? 'instansi-tanpa-nama';
        $tahun = $year; // Berdasarkan struktur tabel di gambar Anda

        // 2. Buat nama direktori yang aman (Output: lampiran-submitter/upn-veteran-jatim-2026)
        $safeFolderName = Str::slug($namaInstansi) . '-' . $tahun;
        $directoryPath = 'lampiran-submitter/' . $safeFolderName;

        // 3. Looping dan simpan file
        foreach ($documents as $key => $file) {
            // Ambil nama file asli tanpa ekstensi
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            // Format aman: timestamp_nama-file-asli.pdf
            // Contoh: 1713600000_surat-pengantar.pdf
            $safeFileName = time() . '_' . Str::slug($originalName) . '.' . $extension;

            // Simpan ke Laravel Storage (disimpan di: storage/app/public/lampiran-submitter/...)
            $storedPath = $file->storeAs($directoryPath, $safeFileName, 'public');

            // Simpan URL public-nya ke array untuk database
            $paths[$key] = '/storage/' . $storedPath;
        }

        return $paths;
    }


    /**
     * 1. Ambil Semua Pertanyaan & Jawaban (Single Form)
     */
    public function getAllQuestionsWithAnswers($assessment)
    {
        // Langsung panggil repository menggunakan ID Assessment dari DTO
        $questions = $this->repository->getAllQuestionsWithExistingAnswers($assessment);

        if ($questions->isEmpty()) {
            throw new Exception("Master data soal belum tersedia.", 404);
        }

        return [
            'assessment_id' => $assessment->id,
            'questions'     => $questions
        ];
    }

    /**
     * 2. Auto-Save Progress (Simpan periodik / Single Form)
     */
    public function autoSaveProgress(SubmitterDTO $dto)
    {
        $assessment = $this->getActiveAssessmentOrFail($dto->userId);

        // Pagar Keamanan: Tolak jika sudah dikunci
        if (in_array($assessment->status, ['SUBMITTED', 'REVIEWING', 'REVIEWED', 'PUBLISHED'])) {
            throw new Exception("Akses ditolak: Asesmen tidak bisa diubah karena sudah dikunci (Final).", 403);
        }

        // Sanitasi Data
        $sanitizedAnswers = [];
        foreach ($dto->answers as $ans) {
            // Abaikan jika data kosong (membantu payload auto-save yang mungkin parsial)
            if (!isset($ans['indicator_id'])) continue;

            $sanitizedAnswers[] = [
                'question_id' => $ans['indicator_id'],
                'claim_value' => $ans['claim_value'] ?? null,
                'evidence_url' => filter_var($ans['evidence_url'] ?? '', FILTER_SANITIZE_URL),
                'assessment_id' => $assessment->id // Inject ID internal, bukan dari request
            ];
        }

        // Lakukan Upsert di level Repository
        $this->repository->upsertAnswers($assessment->id, $sanitizedAnswers);

        return true;
    }

    /**
     * 3. Final Lock (Validasi dan Penguncian)
     */
    public function lockAssessment($assessment)
    {

        // cek total pertanyaan
        // cek yang telah dijawab oleh user, lalu jika ada pertanyaan yang belum terisi opsi jawabannya, maka otomatis tidak bisa di lock 
        $totalQuestions = $this->repository->countTotalMandatoryQuestions();
        $answered = $this->repository->countValidAnswers($assessment);

        if ($answered < $totalQuestions) {
            throw new Exception("Validasi kelengkapan gagal: Anda baru menjawab {$answered} dari {$totalQuestions} indikator. Harap lengkapi semua sebelum Final Submit.", 422);
        }

        // Kunci Data
        return $this->repository->updateStatusAssessment($assessment->id, 'SUBMITTED');
    }

    /**
     * 4. Hitung Estimasi Total (Preview Keseluruhan Form)
     */
    public function calculateTotalPreview($assessment)
    {
        $answers = $this->repository->getAllAnswersByAssessment($assessment->id);

        $totalEstimatedScore = 0;
        foreach ($answers as $answer) {
            // TODO: Ganti dengan formula baku PatriotMetric (Normalisasi/Bobot)
            if (is_numeric($answer->skor_sistem)) {
                $totalEstimatedScore += $answer->skor_sistem;
            }
        }

        return [
            'estimated_score' => $totalEstimatedScore,
            'label' => "Estimasi Skor Kasar",
            'details' => "Skor ini bersifat estimasi sebelum verifikasi Reviewer."
        ];
    }

    /**
     * 5. Ambil Progres Saat Ini (Untuk UI Progress Bar)
     */
    public function getCurrentProgress(SubmitterDTO $dto)
    {
        $assessment = $this->getActiveAssessmentOrFail($dto->userId);

        $totalQuestions = $this->repository->countTotalMandatoryQuestions();
        $answered = $this->repository->countValidAnswers($assessment->id);

        $percentage = $totalQuestions > 0 ? round(($answered / $totalQuestions) * 100) : 0;

        return [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answered,
            'percentage' => $percentage,
            'is_completed' => $answered >= $totalQuestions
        ];
    }
}
