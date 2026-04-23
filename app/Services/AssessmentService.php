<?php

namespace App\Services;

use App\Repositories\AssessmentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use App\DTO\AssessmentDTO\AssessmentDTO;
use App\DTO\AssessmentDTO\BaselineDTO;
use App\DTO\AssessmentDTO\JawabanDTO;
use App\Repositories\PertanyaanRepository;
use Illuminate\Support\Str;

/**
 * @property \App\Repositories\AssessmentRepository $repository
 */
class AssessmentService extends BaseService
{
    protected $pertanyaanRepository;

    public function __construct(
        AssessmentRepository $AssessmentRepository,
        PertanyaanRepository $pertanyaanRepository
    ) {
        // Parent constructor mengikat AssessmentRepository sebagai repository utama
        parent::__construct($AssessmentRepository);
        $this->pertanyaanRepository = $pertanyaanRepository;
    }

    const MODE_READ = 'read';
    const MODE_WRITE = 'write';
    const MODE_ANY   = 'any';

    // DONE
    private function ensureUserIsActive($userId)
    {
        if (!$this->repository->isUserActive($userId)) {
            throw new Exception("Akses Ditolak: Akun Anda tidak aktif atau ditangguhkan.", 403);
        }
    }

    // DONE
    public function validate(AssessmentDTO $dto, string $mode)
    {
        // 1. Pastikan User Aktif
        $this->ensureUserIsActive($dto->userId);

        // 2. Ambil Sesi Asesmen Aktif
        $assessment = $this->repository->findActiveAssessmentByUserId($dto->userId);
        if (!$assessment) {
            throw new Exception("Sesi asesmen aktif tidak ditemukan untuk tahun berjalan.", 404);
        }

        // 3. Dispatcher Mode
        return match ($mode) {
            self::MODE_WRITE => $this->guardUpdateAccess($assessment),
            self::MODE_READ  => $this->guardReadAccess($assessment),
            self::MODE_ANY   => $assessment,
            default          => throw new Exception("Mode validasi tidak dikenali.", 500),
        };
    }

    // DONE
    private function guardUpdateAccess($assessment)
    {
        if (!in_array($assessment->status, ['ACTIVE', 'IN_PROGRESS'])) {
            throw new Exception("Gagal: Asesmen berstatus {$assessment->status} tidak dapat diubah.", 403);
        }
        return $assessment;
    }

    // DONE
    private function guardReadAccess($assessment)
    {
        // Izinkan pembacaan jika sudah dikirim atau dinilai
        if (!in_array($assessment->status, ['SUBMITTED', 'GRADED'])) {
            throw new Exception("Gagal: Hasil asesmen belum tersedia untuk dilihat.", 403);
        }
        return $assessment;
    }

    // CHECK
    public function upsertBaseline(BaselineDTO $dto)
    {
        $assessment = $this->repository->findActiveAssessmentByUserId($dto->userId);
        if (!$assessment) throw new Exception("Sesi asesmen aktif tidak ditemukan.", 404);

        return DB::transaction(function () use ($dto, $assessment) {

            // 1. DATA HYDRATION: Ambil data lama
            $existingIdentitas = $this->repository->findIdentitasByPengumpulanId($assessment->id);
            $existingInstitusi = $this->repository->findInstitusiById($assessment->institution_id);

            // 2. Update Institusi (Gunakan Null-safe operator ?-> untuk mencegah crash)
            $this->repository->updateInstitusi($assessment->institution_id, [
                'nama_institusi'  => $dto->namaInstitusi ?? $existingInstitusi?->nama_institusi,
                'jenis_institusi' => $dto->jenisInstitusi ?? $existingInstitusi?->jenis_institusi
            ]);

            // 3. Proses File Upload (HANYA KETIKA CREATE)
            $finalDocumentsJson = $existingIdentitas?->legal_documents ?? json_encode([]);

            // ZERO-GAP LOGIC: 
            // Hanya jalankan processLegalDocuments JIKA $existingIdentitas kosong (Create)
            // Jika ini Update, blok if ini akan dilewati dan file lama akan dipertahankan.
            if (!$existingIdentitas && !empty($dto->legalDocuments)) {
                // Eksekusi pemindahan file ke Storage
                $documentPaths = $this->processLegalDocuments($dto->legalDocuments, $assessment);
                $finalDocumentsJson = json_encode($documentPaths);
            }

            // 4. Update Identitas (Gunakan Null-safe operator secara ketat)
            $identitasData = [
                'pengumpulan_id'  => $assessment->id,
                'jml_mahasiswa'   => $dto->jmlMhs ?? $existingIdentitas?->jml_mahasiswa ?? 0,
                'jml_dosen'       => $dto->jmlDosen ?? $existingIdentitas?->jml_dosen ?? 0,
                'jml_tendik'      => $dto->jmlTendik ?? $existingIdentitas?->jml_tendik ?? 0,
                'jml_prodi'       => $dto->jmlProdi ?? $existingIdentitas?->jml_prodi ?? 0,
                'jml_ukm'         => $dto->jmlUkm ?? $existingIdentitas?->jml_ukm ?? 0,
                'jml_fakultas'    => $dto->jmlFakultas ?? $existingIdentitas?->jml_fakultas ?? 0,
                'visi'            => $dto->visi ?? $existingIdentitas?->visi ?? null,
                'misi'            => $dto->misi ?? $existingIdentitas?->misi ?? null,
                'legal_documents' => $finalDocumentsJson,
                'is_verified'     => $existingIdentitas?->is_verified ?? false,
            ];

            // Pastikan repository menggunakan updateOrCreate di balik layar
            $identitas = $this->repository->upsertIdentitas($assessment->id, $identitasData);

            // 5. Update Agama secara Selektif
            if (!empty($dto->dataAgama)) {
                foreach ($dto->dataAgama as $namaAgama => $jumlah) {
                    // Validasi Enum sebelum insert untuk mencegah SQL Error
                    $allowedAgama = ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu'];
                    if (in_array(strtolower($namaAgama), $allowedAgama)) {
                        $this->repository->upsertAgama($identitas->id, strtolower($namaAgama), $jumlah);
                    }
                }
            }

            return $identitas;
        });
    }

    // DONE
    private function processLegalDocuments(array $documents, $assessment): array
    {
        $paths = [];
        $safeFolderName = Str::slug($assessment->institusi->name ?? 'unknown') . '-' . $assessment->tahun_periode;
        $directoryPath = 'lampiran-peserta/' . $safeFolderName;

        foreach ($documents as $key => $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = time() . '_' . Str::slug($originalName) . '.' . $file->getClientOriginalExtension();

            $storedPath = $file->storeAs($directoryPath, $safeFileName, 'public');
            $paths[$key] = '/storage/' . $storedPath;
        }

        return $paths;
    }

    /**
     * 1. Ambil Semua Pertanyaan & Jawaban (Single Form)
     */
    public function getAllQuestionsWithAnswers($assessment)
    {
        $questions = $this->pertanyaanRepository->getAllQuestionsWithExistingAnswers($assessment);

        if ($questions->isEmpty()) {
            throw new Exception("Master data pertanyaan belum dikonfigurasi oleh Admin.", 404);
        }

        return [
            'assessment_id' => $assessment->id,
            'status'        => $assessment->status,
            'questions'     => $questions
        ];
    }

    // DONE
    public function getAllPertanyaan()
    {
        return $this->pertanyaanRepository->getPertanyaanWithOpsiJawaban();
    }

    /**
     * 2. Auto-Save Progress (Simpan periodik / Single Form)
     CHECK
     */
    // public function autoSaveProgress(AssessmentDTO $dto)
    // {
    //     // Gunakan mode WRITE untuk memastikan form belum dikunci
    //     $assessment = $this->validate($dto, self::MODE_WRITE);

    //     $sanitizedAnswers = [];
    //     foreach ($dto->answers as $ans) {
    //         // Validasi minimal: harus ada ID Pertanyaan dan ID Jawaban (opsi)
    //         if (!isset($ans['pertanyaan_id']) || !isset($ans['jawaban_id'])) continue;

    //         $sanitizedAnswers[] = [
    //             'pertanyaan_id'      => $ans['pertanyaan_id'],
    //             'jawaban_id'         => $ans['jawaban_id'],
    //             'jawaban_teks'       => $ans['jawaban_teks'] ?? null,
    //             'tautan_bukti_drive' => filter_var($ans['tautan_bukti_drive'] ?? '', FILTER_SANITIZE_URL),
    //         ];
    //     }

    //     if (empty($sanitizedAnswers)) return false;

    //     $this->repository->upsertAnswers($assessment->id, $sanitizedAnswers);

    //     // Update status ke IN_PROGRESS jika masih ACTIVE
    //     if ($assessment->status === 'ACTIVE') {
    //         $this->repository->updateStatusAssessment($assessment->id, 'IN_PROGRESS');
    //     }

    //     return true;
    // }

    public function storeJawaban(JawabanDTO $dto)
    {
        // 1. Fetch info pertanyaan untuk Technical Interrogation
        $pertanyaan = $this->pertanyaanRepository->findPertanyaanById($dto->pertanyaanId);
        if (!$pertanyaan) throw new Exception("Pertanyaan tidak ditemukan.", 404);

        // 2. Build Payload Dasar
        $payload = [
            'submission_id' => $dto->submissionId,
            'pertanyaan_id' => $dto->pertanyaanId,
        ];

        // 3. Jalankan Auto-Sync (Merge hasil sync ke payload utama)
        $syncData = $this->jawabanAutoSync($dto, $pertanyaan);
        $payload = array_merge($payload, $syncData);

        // 4. KONDISI 3 & 4: Tambahan Bukti dan Note
        $payload['tautan_bukti_drive'] = $dto->tautanBukti;
        $payload['note_reviewer']      = $dto->noteReviewer;

        // 5. Delegasi ke Repository (Upsert)
        return $this->repository->upsertJawaban($payload);
    }

    /**
     * Private Helper: Sinkronisasi Dua Arah & Kalkulasi Skor Real-time
     */
    private function jawabanAutoSync(JawabanDTO $dto, $pertanyaan): array
    {
        $data = [];

        if ($pertanyaan->tipe === 'pilihan_ganda') {
            // KONDISI 1: User kirim ID -> Ambil Teks & Skor
            $data['jawaban_id'] = $dto->jawabanId;

            $opsi = $this->pertanyaanRepository->findOpsiById($dto->jawabanId);
            $data['jawaban_teks'] = $opsi ? $opsi->opsi_jawaban : null;
            $data['skor_sistem']  = $opsi ? $opsi->value : 0;
        } else {
            // KONDISI 2: User kirim Teks (Angka) -> Cari ID & Skor yang sesuai
            $data['jawaban_teks'] = $dto->jawabanTeks;

            $matchingOpsi = $this->pertanyaanRepository->findMatchingOpsiByValue(
                $dto->pertanyaanId,
                $dto->jawabanTeks
            );

            if ($matchingOpsi) {
                $data['jawaban_id']  = $matchingOpsi->id;
                $data['skor_sistem'] = $matchingOpsi->value;
            } else {
                $data['jawaban_id']  = null;
                $data['skor_sistem'] = 0;
            }
        }

        return $data; // WAJIB return agar bisa dipakai di storeJawaban
    }

    public function getAssignedReviews(int $reviewerId)
    {
        // 1. Pastikan akun Reviewer masih aktif (menggunakan fungsi yang sudah ada)
        $this->ensureUserIsActive($reviewerId);

        // 2. Tarik data dari Repository
        $assessments = $this->repository->getAssignedAssessmentsByReviewer($reviewerId);

        // 3. (Opsional) Transformasi data jika diperlukan sebelum dikirim ke Controller
        // Misalnya menghitung statistik ringan untuk dashboard Reviewer
        $summary = [
            'total_tugas' => $assessments->count(),
            'menunggu_review' => $assessments->where('status', 'SUBMITTED')->count(),
            'selesai_review' => $assessments->where('status', 'GRADED')->count(),
            'yang_belum_direview' => $assessments->where('status', 'SUBMITTED')->values(),
            'daftar_asesmen' => $assessments->values() // Reset key array
        ];

        return $summary;
    }

    /**
     * 3. Final Lock (Validasi dan Penguncian)
    DONE
     */
    public function lockAssessment($assessment)
    {

        $totalQuestions = $this->pertanyaanRepository->countTotalMandatoryQuestions();
        $answered       = $this->repository->countValidAnswers($assessment);

        if ($answered < $totalQuestions) {
            throw new Exception("Gagal Submit: Anda baru menjawab {$answered} dari {$totalQuestions} soal. Harap lengkapi semua jawaban.", 422);
        }

        return $this->repository->updateStatusAssessment($assessment->id, 'SUBMITTED');
    }

    /**
     * 4. Hitung Estimasi Total (Preview Keseluruhan Form)
     DONE
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
     CHECK
     */
    public function getCurrentProgress(AssessmentDTO $dto)
    {
        $assessment = $this->repository->findActiveAssessmentByUserId($dto->userId);
        if (!$assessment) return ['percentage' => 0];

        $totalQuestions = $this->pertanyaanRepository->countTotalMandatoryQuestions();
        $answered       = $this->repository->countValidAnswers($assessment);

        return [
            'total_questions'    => $totalQuestions,
            'answered_questions' => $answered,
            'percentage'         => $totalQuestions > 0 ? round(($answered / $totalQuestions) * 100) : 0,
            'is_completed'       => $answered >= $totalQuestions
        ];
    }

    /**
     * Mengambil detail profil peserta (institusi, identitas, agama, dokumen) untuk reviewer
     */
    public function getDetailReviewTasks(int $reviewerId, int $pesertaId)
    {
        // 1. Pastikan akun Reviewer masih aktif
        $this->ensureUserIsActive($reviewerId);

        // 2. Tarik data dari Repository
        $assessment = $this->repository->getDetailAssessmentByReviewer($reviewerId, $pesertaId);

        if (!$assessment) {
            throw new Exception("Data asesmen tidak ditemukan atau Anda tidak memiliki hak akses.", 404);
        }

        $identitas = $assessment->identitas;

        // Memformat data sesuai spesifikasi docs-api.md
        return [
            'pengumpulan' => [
                'id' => $assessment->id,
                'status' => $assessment->status,
                'total_skor_sistem' => $assessment->total_skor_sistem,
                'total_skor_akhir' => $assessment->total_skor_akhir,
                'tahun_periode' => $assessment->tahun_periode,
            ],
            'institusi' => $assessment->institusi,
            'profil_peserta' => $identitas ? [
                'visi' => $identitas->visi,
                'misi' => $identitas->misi,
                'jml_fakultas' => $identitas->jml_fakultas,
                'jml_studi' => $identitas->jml_prodi,
                'jml_dosen' => $identitas->jml_dosen,
                'jml_tendik' => $identitas->jml_tendik,
                'jml_mhs' => $identitas->jml_mahasiswa,
                'jml_ukm' => $identitas->jml_ukm,
                'berkas_pendukung' => $identitas->legal_documents,
                'agama' => $identitas->agamas->mapWithKeys(function ($item) {
                    return [strtolower($item->agama) => $item->jumlah];
                })
            ] : null
        ];
    }
}
