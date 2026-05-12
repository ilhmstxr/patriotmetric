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
    protected $timelineRepository;

    public function __construct(
        AssessmentRepository $AssessmentRepository,
        PertanyaanRepository $pertanyaanRepository,
        \App\Repositories\TimelineRepository $timelineRepository
    ) {
        // Parent constructor mengikat AssessmentRepository sebagai repository utama
        parent::__construct($AssessmentRepository);
        $this->pertanyaanRepository = $pertanyaanRepository;
        $this->timelineRepository = $timelineRepository;
    }

    const MODE_READ = 'read';
    const MODE_WRITE = 'write';
    const MODE_ANY = 'any';

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
            self::MODE_READ => $this->guardReadAccess($assessment),
            self::MODE_ANY => $assessment,
            default => throw new Exception("Mode validasi tidak dikenali.", 500),
        };
    }

    // DONE
    private function guardUpdateAccess($assessment)
    {
        if (!in_array($assessment->status, ['ACTIVE', 'IN_PROGRESS'])) {
            throw new Exception("Gagal: Asesmen berstatus {$assessment->status} tidak dapat diubah.", 403);
        }

        $timelineCheck = $this->timelineRepository->canSubmit($assessment->tahun_periode);
        if (!$timelineCheck['allowed']) {
            throw new Exception("Gagal: " . $timelineCheck['reason'], 403);
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
        if (!$assessment)
            throw new Exception("Sesi asesmen aktif tidak ditemukan.", 404);

        return DB::transaction(function () use ($dto, $assessment) {

            // 1. DATA HYDRATION: Ambil data lama
            $existingIdentitas = $this->repository->findIdentitasByAssessmentId($assessment->id);
            $existingInstitusi = $this->repository->findInstitusiById($assessment->institution_id);

            // 2. Update Institusi (Gunakan Null-safe operator ?-> untuk mencegah crash)
            $this->repository->updateInstitusi($assessment->institution_id, [
                'nama_institusi' => $dto->namaInstitusi ?? $existingInstitusi?->nama_institusi,
                'jenis_institusi' => $dto->jenisInstitusi ?? $existingInstitusi?->jenis_institusi
            ]);

            // 3. Proses File Upload (HANYA KETIKA CREATE)
            $finalDocumentsJson = $existingIdentitas?->legal_documents ?? [];

            // ZERO-GAP LOGIC: 
            // Hanya jalankan processLegalDocuments JIKA $existingIdentitas kosong (Create)
            // Jika ini Update, blok if ini akan dilewati dan file lama akan dipertahankan.
            if (!$existingIdentitas && !empty($dto->legalDocuments)) {
                // Eksekusi pemindahan file ke Storage
                $documentPaths = $this->processLegalDocuments($dto->legalDocuments, $assessment);
                $finalDocumentsJson = $documentPaths;
            }

            // 4. Update Identitas (Gunakan Null-safe operator secara ketat)
            $identitasData = [
                'Assessment_id' => $assessment->id,
                'jml_mahasiswa' => $dto->jmlMhs ?? $existingIdentitas?->jml_mahasiswa ?? 0,
                'jml_dosen' => $dto->jmlDosen ?? $existingIdentitas?->jml_dosen ?? 0,
                'jml_tendik' => $dto->jmlTendik ?? $existingIdentitas?->jml_tendik ?? 0,
                'jml_prodi' => $dto->jmlProdi ?? $existingIdentitas?->jml_prodi ?? 0,
                'jml_ukm' => $dto->jmlUkm ?? $existingIdentitas?->jml_ukm ?? 0,
                'jml_fakultas' => $dto->jmlFakultas ?? $existingIdentitas?->jml_fakultas ?? 0,
                'visi' => $dto->visi ?? $existingIdentitas?->visi ?? null,
                'misi' => $dto->misi ?? $existingIdentitas?->misi ?? null,
                'legal_documents' => $finalDocumentsJson,
                'is_verified' => $existingIdentitas?->is_verified ?? false,
            ];

            // Pastikan repository menggunakan updateOrCreate di balik layar
            $identitas = $this->repository->upsertIdentitas($assessment->id, $identitasData);

            // 5. Update Agama secara Selektif
            if (!empty($dto->dataAgama)) {
                foreach ($dto->dataAgama as $namaAgama => $jumlah) {
                    // Validasi Enum sebelum insert untuk mencegah SQL Error
                    $allowedAgama = ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu', 'kepercayaan terhadap tuhan yang maha esa'];
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
        $safeFolderName = Str::slug($assessment->institusi?->nama_institusi ?? 'unknown') . '-' . $assessment->tahun_periode;
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

        $timelineCheck = $this->timelineRepository->canSubmit($assessment->tahun_periode);
        $isEditEnabled = $timelineCheck['allowed'];

        return [
            'assessment_id' => $assessment->id,
            'status' => $assessment->status,
            'is_edit_enabled' => $isEditEnabled,
            'lock_reason'     => $timelineCheck['reason'] ?? null,
            'questions'       => $questions,
            'profil'          => $this->getProfilData($assessment),
        ];
    }

    /**
     * Mengambil data profil identitas peserta beserta jumlah agama aktif.
     * Digunakan untuk kalkulasi formula preview di sisi frontend.
     */
    private function getProfilData($assessment): array
    {
        $identitas = $this->repository->getIdentitasWithAgama($assessment->id);

        return [
            'jml_mahasiswa' => $identitas?->jml_mahasiswa ?? 0,
            'jml_dosen' => $identitas?->jml_dosen ?? 0,
            'jml_tendik' => $identitas?->jml_tendik ?? 0,
            'jml_prodi' => $identitas?->jml_prodi ?? 0,
            'jml_ukm' => $identitas?->jml_ukm ?? 0,
            'jml_fakultas' => $identitas?->jml_fakultas ?? 0,
            'jml_ormawa' => $identitas?->jml_ormawa ?? 0,
            // Jumlah jenis agama/kepercayaan yang memiliki penganut > 0 (untuk B.20)
            'jml_agama_aktif' => $identitas
                ? $identitas->agamas->where('jumlah', '>', 0)->count()
                : 0,
        ];
    }

    // DONE
    public function getAllPertanyaan()
    {
        return $this->pertanyaanRepository->getPertanyaanWithOpsiJawaban();
    }


    public function getProfilePeserta(int $pesertaId)
    {
        return $this->repository->getProfilePeserta($pesertaId);
    }

    public function storeJawaban(JawabanDTO $dto)
    {
        // 1. Fetch info pertanyaan untuk Technical Interrogation
        $pertanyaan = $this->pertanyaanRepository->findPertanyaanById($dto->pertanyaanId);
        if (!$pertanyaan)
            throw new Exception("Pertanyaan tidak ditemukan.", 404);

        // 2. Build Payload Dasar
        $payload = [
            'assessment_id' => $dto->submissionId,
            'pertanyaan_id' => $dto->pertanyaanId,
        ];

        // 3. Jalankan Auto-Sync (Merge hasil sync ke payload utama)
        $syncData = $this->jawabanAutoSync($dto, $pertanyaan);
        $payload = array_merge($payload, $syncData);

        // 4. KONDISI 3 & 4: Tambahan Bukti dan Note
        $payload['tautan_bukti_drive'] = $dto->tautanBukti;
        $payload['note_reviewer'] = $dto->noteReviewer;

        // 5. Aturan skor: hanya dihitung saat jawaban DAN tautan bukti keduanya terisi.
        //    Soal otomatis_sistem dikecualikan karena tidak butuh tautan bukti.
        $jawabanTeksFilled = false;
        if (isset($payload['jawaban_teks'])) {
            $teks = $payload['jawaban_teks'];
            if (is_array($teks)) {
                $raw = $teks['raw_input'] ?? '';
                $jawabanTeksFilled = trim((string) $raw) !== '';
            } else {
                $jawabanTeksFilled = trim((string) $teks) !== '';
            }
        }
        $hasJawaban = !empty($payload['jawaban_id']) || $jawabanTeksFilled;
        
        $hasTautan = !empty($payload['tautan_bukti_drive']) && trim((string) $payload['tautan_bukti_drive']) !== '';
        $isOtomatis = ($pertanyaan->tipe ?? null) === 'otomatis_sistem';
        if (!$isOtomatis && (!$hasJawaban || !$hasTautan)) {
            $payload['skor_sistem'] = 0;
        }

        // 6. Delegasi ke Repository (Upsert)
        return $this->repository->upsertJawaban($payload);
    }

    /**
     * Mengembalikan token versi (timestamp ISO) untuk validasi cache rubrik di frontend.
     * Frontend melakukan stale-while-revalidate: jika versi sama, tidak perlu refetch full data.
     */
    public function getQuestionsVersion($assessment): array
    {
        $latestPertanyaan = $this->pertanyaanRepository->getLatestPertanyaanUpdate();
        $latestJawaban = $this->repository->getLatestJawabanUpdate($assessment->id);

        $components = array_filter([$latestPertanyaan, $latestJawaban, $assessment->updated_at]);
        $version = $components ? max($components) : null;

        return [
            'version' => $version ? (string) $version : null,
            'pertanyaan_updated_at' => $latestPertanyaan ? (string) $latestPertanyaan : null,
            'jawaban_updated_at' => $latestJawaban ? (string) $latestJawaban : null,
            'assessment_status' => $assessment->status,
        ];
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
            $teksKeterangan = $opsi ? $opsi->keterangan : null;
            
            $data['jawaban_teks'] = [
                'raw_input' => $teksKeterangan,
                'calculated_percentage' => null
            ];
            
            $data['skor_sistem'] = $opsi ? (int) $opsi->opsi_jawaban : 0; // Score in DB is stored in opsi_jawaban
        } else {
            // KONDISI 2: User kirim Teks (Angka) -> Cari ID & Skor yang sesuai
            $rawJawabanTeks = $dto->jawabanTeks ?? (string) $dto->jawabanId;

            // SPECIAL: B.13 menyimpan JSON lengkap {lokal:{label,nilai,poin},...,total_poin}
            if (($pertanyaan->kode_pertanyaan ?? null) === 'B.13') {
                $decoded = is_string($rawJawabanTeks) ? json_decode($rawJawabanTeks, true) : null;
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $data['jawaban_teks'] = $rawJawabanTeks;
                } else {
                    $data['jawaban_teks'] = $rawJawabanTeks;
                }
                $data['jawaban_id'] = null;
                $data['skor_sistem'] = (int) ($decoded['total_poin'] ?? 0);
                return $data;
            }

            $decodedJawaban = null;

            // 1. Defensive Parsing: Coba decode stringified JSON dari FE
            if (is_string($rawJawabanTeks)) {
                $decoded = json_decode($rawJawabanTeks, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $decodedJawaban = $decoded;
                } else {
                    $decodedJawaban = $rawJawabanTeks; // Fallback ke string murni
                }
            } elseif (is_array($rawJawabanTeks)) {
                $decodedJawaban = $rawJawabanTeks;
            }

            // 2. Normalisasi Struktur & Kalkulasi Skor
            if ($pertanyaan->code === 'B.13') {
                // KHUSUS B.13: Payload berisi rincian 4 skala + total_poin
                $data['jawaban_teks'] = is_array($decodedJawaban) ? $decodedJawaban : ['raw_input' => $decodedJawaban];
                $data['skor_sistem'] = is_array($decodedJawaban) ? (float) ($decodedJawaban['total_poin'] ?? 0) : 0;
                $data['jawaban_id'] = null;
                return $data;
            }

            $normalizedJawaban = [
                'raw_input' => is_array($decodedJawaban) 
                    ? ($decodedJawaban['raw_input'] ?? null) 
                    : $decodedJawaban, // Jika isian tunggal (string murni), taruh di sini
                    
                'calculated_percentage' => is_array($decodedJawaban) 
                    ? ($decodedJawaban['calculated_percentage'] ?? null) 
                    : null,
            ];

            $data['jawaban_teks'] = $normalizedJawaban;

            // Cek untuk kalkulasi skor otomatis (Gunakan calculated_percentage jika ada)
            $calculatedValue = $normalizedJawaban['calculated_percentage'] ?? $normalizedJawaban['raw_input'];

            $matchingOpsi = $this->pertanyaanRepository->findMatchingOpsiByValue(
                $dto->pertanyaanId,
                $calculatedValue
            );

            if ($matchingOpsi) {
                $data['jawaban_id'] = $matchingOpsi->id;
                $data['skor_sistem'] = (int) $matchingOpsi->opsi_jawaban; // Score is stored in opsi_jawaban
            } else {
                $data['jawaban_id'] = null;
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
            'yang_belum_direview' => $assessments->whereIn('status', ['ACTIVE', 'IN_PROGRESS', 'SUBMITTED'])->values(),
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
        $answered = $this->repository->countValidAnswers($assessment);

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
        if (!$assessment)
            return ['percentage' => 0];

        $totalQuestions = $this->pertanyaanRepository->countTotalMandatoryQuestions();
        $answered = $this->repository->countValidAnswers($assessment);

        return [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answered,
            'percentage' => $totalQuestions > 0 ? round(($answered / $totalQuestions) * 100) : 0,
            'is_completed' => $answered >= $totalQuestions
        ];
    }

    /**
     * Mengambil detail profil peserta + rubrik + jawaban untuk reviewer
     * Hanya menampilkan jawaban ketika status SUBMITTED
     */
    public function getDetailReviewTasks(int $reviewerId, int $pesertaId)
    {
        // 1. Pastikan akun Reviewer masih aktif
        $this->ensureUserIsActive($reviewerId);

        // 2. Tarik data dari Repository (now includes jawabans with pertanyaan)
        $assessment = $this->repository->getDetailAssessmentByReviewer($reviewerId, $pesertaId);

        if (!$assessment) {
            throw new Exception("Data asesmen tidak ditemukan atau Anda tidak memiliki hak akses.", 404);
        }

        $identitas = $assessment->identitas;

        // 3. Get all pertanyaan with opsi jawaban (for reviewer guide)
        $allPertanyaan = $this->pertanyaanRepository->getPertanyaanWithOpsiJawaban();

        // 4. Build jawaban map (pertanyaan_id => jawaban data) from peserta's answers
        $jawabanMap = [];
        if (in_array($assessment->status, ['SUBMITTED', 'GRADED'])) {
            foreach ($assessment->jawabans as $jawaban) {
                $jawabanMap[$jawaban->pertanyaan_id] = [
                    'jawaban_id' => $jawaban->jawaban_id,
                    'jawaban_teks' => $this->formatJawabanTeksDisplay($jawaban->jawaban_teks),
                    'tautan_bukti_drive' => $jawaban->tautan_bukti_drive,
                    'skor_sistem' => $jawaban->skor_sistem,
                    'skor_validasi_reviewer' => $jawaban->skor_validasi_reviewer,
                    'opsi_dipilih' => $jawaban->jawabanOpsi ? [
                        'id' => $jawaban->jawabanOpsi->id,
                        'opsi_jawaban' => $jawaban->jawabanOpsi->opsi_jawaban,
                        'keterangan' => $jawaban->jawabanOpsi->keterangan,
                        'value' => $jawaban->jawabanOpsi->value,
                    ] : null,
                ];
            }
        }


        // Masking answers if assessment is still IN_PROGRESS
        if ($assessment->status === 'IN_PROGRESS') {
            $jawabanMap = [];
        }

        // Load JSON for isian_singkat bobot
        $isianSingkatGuides = [];
        $jsonPath = storage_path('app/isian_singkat_bobot.json');
        if (file_exists($jsonPath)) {
            $isianSingkatGuides = json_decode(file_get_contents($jsonPath), true) ?? [];
        }

        // 5. Group pertanyaan by kategori with jawaban
        $rubrikData = [];
        foreach ($allPertanyaan as $pertanyaan) {
            $kategoriName = $pertanyaan->kategori->nama_kategori ?? 'Tanpa Kategori';

            if (!isset($rubrikData[$kategoriName])) {
                $rubrikData[$kategoriName] = [
                    'kategori' => $kategoriName,
                    'pertanyaan_count' => 0,
                    'bobot_maksimal' => 0,
                    'pertanyaan' => [],
                ];
            }

            $rubrikData[$kategoriName]['pertanyaan_count']++;
            $rubrikData[$kategoriName]['bobot_maksimal'] += 5; // Max 5 per rubrik

            $rubrikData[$kategoriName]['pertanyaan'][] = [
                'id' => $pertanyaan->id,
                'kode_pertanyaan' => $pertanyaan->kode_pertanyaan,
                'teks_pertanyaan' => $pertanyaan->teks_pertanyaan,
                'kebutuhan_bukti' => $pertanyaan->kebutuhan_bukti,
                'tipe' => $pertanyaan->tipe,
                // 'skor_maksimal' => $pertanyaan->skor_maksimal,
                'opsi_jawaban' => (function () use ($pertanyaan, $isianSingkatGuides) {
                    // Cek jika ini isian singkat, dan kita punya json guide
                    if ($pertanyaan->tipe === 'isian_singkat' && !empty($isianSingkatGuides)) {
                        $teks = strtolower(trim($pertanyaan->teks_pertanyaan));
                        $bestMatch = null;
                        $highestPercent = 0;
                        
                        foreach ($isianSingkatGuides as $guide) {
                            if ($teks === strtolower(trim($guide['indikator_implementasi']))) {
                                $bestMatch = $guide;
                                break;
                            }
                        }
                        
                        // Jika match persis (hardcode/exact match)
                        if ($bestMatch) {
                            $mappedOpsi = [];
                            foreach ($bestMatch['skor_bobot'] as $skor => $ket) {
                                $mappedOpsi[] = [
                                    'id' => null, // no db id
                                    'opsi_jawaban' => (string) $skor,
                                    'keterangan' => $ket,
                                    'value' => (int) $skor,
                                ];
                            }
                            return $mappedOpsi;
                        }
                    }

                    // Default behaviour: ambil dari database opsi_jawaban
                    return $pertanyaan->OpsiJawaban->map(function ($opsi) {
                        return [
                            'id' => $opsi->id,
                            'opsi_jawaban' => $opsi->opsi_jawaban,
                            'keterangan' => $opsi->keterangan,
                            'value' => $opsi->value,
                        ];
                    })->toArray();
                })(),
                'jawaban_peserta' => $jawabanMap[$pertanyaan->id] ?? null,
            ];
        }

        return [
            'Assessment' => [
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
                'jml_prodi' => $identitas->jml_prodi,
                'jml_dosen' => $identitas->jml_dosen,
                'jml_tendik' => $identitas->jml_tendik,
                'jml_mhs' => $identitas->jml_mahasiswa,
                'jml_ukm' => $identitas->jml_ukm,
                'jml_ormawa' => $identitas->jml_ormawa ?? 0,
                'berkas_pendukung' => $identitas->legal_documents,
                'agama' => $identitas->agamas->mapWithKeys(function ($item) {
                    return [strtolower($item->agama) => $item->jumlah];
                })
            ] : null,
            'rubrik' => array_values($rubrikData),
            'nama_pic' => $assessment->nama_pic,
            'jabatan_pic' => $assessment->jabatan_pic,
            'no_hp_pic' => $assessment->no_hp_pic,
            'email_pic' => $assessment->user->email ?? null,
        ];
    }

    /**
     * Get hasil data for peserta dashboard — shows raw scores if not yet validated
     */
    public function getHasilData(int $userId)
    {
        $assessment = $this->repository->findActiveAssessmentByUserId($userId);
        if (!$assessment) {
            throw new Exception("Sesi asesmen aktif tidak ditemukan.", 404);
        }

        $timelineCheck = $this->timelineRepository->canViewResults($assessment->tahun_periode);
        if (!$timelineCheck['allowed']) {
            throw new Exception($timelineCheck['reason'], 403);
        }

        if ($assessment->status !== 'PUBLISHED') {
            throw new Exception("Hasil penilaian Anda sedang dalam proses finalisasi dan belum dipublikasikan.", 403);
        }

        // Get all categories with their questions
        $allCategories = $this->pertanyaanRepository->getAllCategoriesWithPertanyaans();

        // Get all existing answers for this assessment
        $answers = $this->repository->getAllAnswersByAssessment($assessment->id)
            ->keyBy('pertanyaan_id');

        $categories = [];
        foreach ($allCategories as $cat) {
            $bobot = $this->getBobotKategori($cat->nama_kategori);
            $catData = [
                'name' => $cat->nama_kategori,
                'bobot' => $bobot,
                'score' => 0,
                'max' => 0,
                'capaian_skor' => 0,
                'items' => [],
            ];

            foreach ($cat->pertanyaans as $pertanyaan) {
                $answer = $answers->get($pertanyaan->id);

                $catData['max'] += 5; // Max score per question is 5

                $displayScore = 0;
                if ($answer) {
                    $displayScore = $answer->skor_validasi_reviewer ?? $answer->skor_sistem ?? 0;
                }
                $catData['score'] += $displayScore;

                $catData['items'][] = [
                    'no' => $pertanyaan->kode_pertanyaan,
                    'title' => $pertanyaan->teks_pertanyaan,
                    'score' => $displayScore,
                    'max' => 5,
                    'jawaban' => $answer ? ($this->formatJawabanTeksDisplay($answer->jawaban_teks) ?? ($answer->jawabanOpsi ? $answer->jawabanOpsi->keterangan : 'Belum diisi')) : 'Belum diisi',
                    'tautan' => $answer ? $answer->tautan_bukti_drive : null,
                    'catatan' => $answer ? $answer->note_reviewer : null,
                    'is_validated' => $answer ? ($answer->skor_validasi_reviewer !== null) : false,
                ];
            }

            // Capaian skor tertimbang = (skor / max) * bobot, 2 desimal
            if ($catData['max'] > 0) {
                $catData['capaian_skor'] = round(($catData['score'] / $catData['max']) * $bobot, 2);
            }

            $categories[] = $catData;
        }

        // Calculate total
        $totalScore = array_sum(array_column($categories, 'score'));
        $totalMax = array_sum(array_column($categories, 'max'));
        $totalCapaianSkor = round(array_sum(array_column($categories, 'capaian_skor')), 2);

        return [
            'status' => $assessment->status,
            'is_validated' => $assessment->status === 'GRADED',
            'total_score' => $totalScore,
            'total_max' => $totalMax,
            'total_capaian_skor' => $totalCapaianSkor,
            'tahun_periode' => $assessment->tahun_periode,
            'institusi' => $assessment->institusi?->nama_institusi ?? '-',
            'categories' => array_values($categories),
        ];
    }

    /**
     * Bobot kategori (hardcoded sementara berdasarkan prefix nama kategori).
     * A. = 20%, B. = 30%, C. = 50%
     */
    private function getBobotKategori(string $namaKategori): float
    {
        $prefix = strtoupper(substr(trim($namaKategori), 0, 2));
        return match ($prefix) {
            'A.' => 20.0,
            'B.' => 30.0,
            'C.' => 50.0,
            default => 0.0,
        };
    }

    /**
     * Save all draft answers in batch (called by "Simpan Draft" button)
     * Updates status to SUBMITTED
     */
    public function saveDraftBatch($assessment, array $answers)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($assessment, $answers) {
            foreach ($answers as $answer) {
                $dto = new \App\DTO\AssessmentDTO\JawabanDTO($assessment->id, $answer);
                $pertanyaan = $this->pertanyaanRepository->findPertanyaanById($dto->pertanyaanId);
                if (!$pertanyaan)
                    continue;

                $payload = [
                    'assessment_id' => $dto->submissionId,
                    'pertanyaan_id' => $dto->pertanyaanId,
                ];

                $syncData = $this->jawabanAutoSync($dto, $pertanyaan);
                $payload = array_merge($payload, $syncData);
                $payload['tautan_bukti_drive'] = $dto->tautanBukti;

                // Aturan skor: hanya dihitung saat jawaban DAN tautan bukti keduanya terisi.
                $jawabanTeksFilled = false;
                if (isset($payload['jawaban_teks'])) {
                    $teks = $payload['jawaban_teks'];
                    if (is_array($teks)) {
                        $raw = $teks['raw_input'] ?? '';
                        $jawabanTeksFilled = trim((string) $raw) !== '';
                    } else {
                        $jawabanTeksFilled = trim((string) $teks) !== '';
                    }
                }
                $hasJawaban = !empty($payload['jawaban_id']) || $jawabanTeksFilled;
                
                $hasTautan = !empty($payload['tautan_bukti_drive']) && trim((string) $payload['tautan_bukti_drive']) !== '';
                $isOtomatis = ($pertanyaan->tipe ?? null) === 'otomatis_sistem';
                if (!$isOtomatis && (!$hasJawaban || !$hasTautan)) {
                    $payload['skor_sistem'] = 0;
                }

                $this->repository->upsertJawaban($payload);
            }

            // Update status to SUBMITTED
            $this->repository->updateStatusAssessment($assessment->id, 'SUBMITTED');

            // Simpan rekap skor persen ke Assessment
            $this->recapAndSaveSkor($assessment);

            return ['saved_count' => count($answers)];
        });
    }

    /**
     * Helper to safely format JSON jawaban_teks for display (Reviewer & Dashboard)
     */
    private function formatJawabanTeksDisplay($teks): ?string
    {
        if (empty($teks))
            return null;
            
        $decoded = is_string($teks) ? json_decode($teks, true) : $teks;
        
        if (is_array($decoded)) {
            $raw = $decoded['raw_input'] ?? '';
            $calc = $decoded['calculated_percentage'] ?? $decoded['calculated'] ?? '';

            if ($raw !== '' && $calc !== '') {
                return "{$raw} (Kalkulasi: {$calc}%)";
            }
            if ($raw !== '')
                return (string) $raw;
            if ($calc !== '')
                return (string) $calc;
        }
        
        return is_string($teks) ? $teks : json_encode($teks);
    }

    /**
     * Menghitung dan menyimpan rekap skor persen total & per-kategori ke kolom skor_rekap_json.
     * Bisa dipanggil setelah peserta submit atau setelah reviewer finalisasi.
     *
     * Struktur JSON yang disimpan:
     * {
     *   "total_persen": 78.50,
     *   "per_kategori": {
     *     "A.": { "nama": "A. ...", "skor": 17, "max": 20, "bobot": 20, "persen_mentah": 85.0, "persen_tertimbang": 17.0 },
     *     ...
     *   },
     *   "updated_at": "2026-05-07T10:00:00+07:00"
     * }
     */
    public function recapAndSaveSkor($assessment): void
    {
        $assessment->refresh();

        $allCategories = $this->pertanyaanRepository->getAllCategoriesWithPertanyaans();

        $answers = $this->repository->getAllAnswersByAssessment($assessment->id)
            ->keyBy('pertanyaan_id');

        $perKategori = [];
        $totalSkor = 0;
        $totalMax  = 0;

        foreach ($allCategories as $cat) {
            $bobot = $this->getBobotKategori($cat->nama_kategori);
            $catSkor = 0;
            $catMax  = 0;

            foreach ($cat->pertanyaans as $pertanyaan) {
                $catMax += 5;
                $answer = $answers->get($pertanyaan->id);
                if ($answer) {
                    $catSkor += $answer->skor_validasi_reviewer ?? $answer->skor_sistem ?? 0;
                }
            }

            $totalSkor += $catSkor;
            $totalMax  += $catMax;

            $prefix = strtoupper(substr(trim($cat->nama_kategori), 0, 2));
            $persenMentah    = $catMax > 0 ? round(($catSkor / $catMax) * 100, 2) : 0;
            $persenTertimbang = round(($persenMentah / 100) * $bobot, 2);

            $perKategori[$prefix] = [
                'nama'              => $cat->nama_kategori,
                'skor'              => $catSkor,
                'max'               => $catMax,
                'bobot'             => $bobot,
                'persen_mentah'     => $persenMentah,
                'persen_tertimbang' => $persenTertimbang,
            ];
        }

        $totalPersen = $totalMax > 0 ? round(($totalSkor / $totalMax) * 100, 2) : 0;

        $rekap = [
            'total_persen'  => $totalPersen,
            'total_skor'    => $totalSkor,
            'total_max'     => $totalMax,
            'per_kategori'  => $perKategori,
            'updated_at'    => now()->toIso8601String(),
        ];

        $this->repository->updateRekapSkor($assessment->id, $rekap);
    }

    public function saveReviewerScores($assessment, array $scores, array $notes)
    {
        return DB::transaction(function () use ($assessment, $scores, $notes) {
            foreach ($scores as $pertanyaanId => $skor) {
                if ($skor === null || $skor === '') continue;

                $skor = min(5, max(0, (int) $skor));

                $this->repository->upsertJawaban([
                    'assessment_id' => $assessment->id,
                    'pertanyaan_id' => (int) $pertanyaanId,
                    'skor_validasi_reviewer' => $skor,
                    'note_reviewer'          => $notes[$pertanyaanId] ?? null,
                ]);
            }

            // Perbarui rekap skor JSON setelah skor reviewer disimpan
            return $this->recapAndSaveSkor($assessment);
        });
    }

    public function finalizeReview($assessment)
    {
        // Cek semua pertanyaan sudah ada skor dari reviewer
        $totalPertanyaan = $this->pertanyaanRepository->countTotalMandatoryQuestions();
        $totalDinilai = $this->repository->countValidReviewerScores($assessment->id);

        if ($totalDinilai < $totalPertanyaan) {
            $belum = $totalPertanyaan - $totalDinilai;
            throw new Exception("Finalisasi gagal: Masih ada {$belum} indikator yang belum diberi skor.", 422);
        }

        // Update status ke GRADED
        $this->repository->updateStatusAssessment($assessment->id, 'GRADED');

        // Update rekap skor final
        return $this->recapAndSaveSkor($assessment);
    }

    public function assignReviewer(int|string $assessmentId, int|string $reviewerId): bool
    {
        return $this->repository->update($assessmentId, ['reviewer_id' => $reviewerId]);
    }
}
