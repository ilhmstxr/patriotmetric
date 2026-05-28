<?php

namespace App\Http\Controllers;

use App\Models\Institusi;
use App\Models\Identitas;
use App\Models\Assessment;
use App\Models\Agama;
use App\Models\Reviewer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    use ApiResponse;

    protected $assessmentRepository;
    protected $userRepository;
    protected $institusiRepository;

    public function __construct(
        \App\Repositories\AssessmentRepository $assessmentRepository,
        \App\Repositories\UserRepository $userRepository,
        \App\Repositories\InstitusiRepository $institusiRepository
    ) {
        $this->assessmentRepository = $assessmentRepository;
        $this->userRepository = $userRepository;
        $this->institusiRepository = $institusiRepository;
    }

    /**
     * POST /api/auth/verification
     * Submit verification form with file uploads.
     * Max 5MB per file. Accepts PDF and images (JPG/PNG).
     */
    public function submit(Request $request)
    {
        try {
            // Get user from token or fallback
            $userId = AuthController::getAuthPeserta();
            $assessment = $this->assessmentRepository->findActiveAssessmentByUserId($userId);

            if (!$assessment) {
                return $this->errorResponse('Data Assessment tidak ditemukan. Silakan register terlebih dahulu.', 404);
            }

            // Validate all inputs — max 5MB (5120 KB) per file
            $validated = $request->validate([
                // Files — Section 1: Dokumen Legal
                'surat_pernyataan' => 'required|file|mimes:pdf|max:5120',
                'sk_pendirian' => 'required|file|mimes:pdf|max:5120',
                // Files — Section 2: Berkas Profil
                'profil_pt' => 'required|file|mimes:pdf|max:5120',
                'logo_url' => 'required|file|mimes:jpeg,jpg,png|max:5120',
                'struktur_organisasi' => 'required|file|mimes:pdf|max:5120',
                'kalender_akademik' => 'required|file|mimes:pdf,jpeg,jpg,png|max:2048',
                // Data Institusi — Section 3
                'nama_pt' => 'required|string|max:255',
                'jenis_pt' => 'required|string|max:100',
                'visi' => 'required|string',
                'misi' => 'required|string',
                'jumlah_fakultas' => 'required|integer|min:0',
                'jumlah_prodi' => 'required|integer|min:0',
                'jumlah_dosen' => 'required|integer|min:0',
                'jumlah_tendik' => 'required|integer|min:0',
                'jumlah_mahasiswa' => 'required|integer|min:0',
                'jumlah_ormawa' => 'required|integer|min:0',
                'jumlah_ukm' => 'required|integer|min:0',
                // Agama
                'agama_islam' => 'required|integer|min:0',
                'agama_kristen' => 'required|integer|min:0',
                'agama_katolik' => 'required|integer|min:0',
                'agama_hindu' => 'required|integer|min:0',
                'agama_buddha' => 'required|integer|min:0',
                'agama_konghucu' => 'required|integer|min:0',
                // PIC
                'nama_pic' => 'required|string|max:255',
                'jabatan_pic' => 'required|string|max:255',
                'no_hp_pic' => 'required|string|max:20',
                'email_pic' => 'required|email|max:255',
            ]);

            // Create safe folder name for file storage
            $safeFolderName = Str::slug($validated['nama_pt']) . '-' . date('Y');
            $directoryPath = 'verifikasi/' . $safeFolderName;

            // Upload all PDF files
            $files = [];
            $pdfFields = ['surat_pernyataan', 'sk_pendirian', 'profil_pt', 'struktur_organisasi'];

            foreach ($pdfFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $safeFileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
                    $storedPath = $file->storeAs($directoryPath, $safeFileName, 'public');
                    $files[$field] = '/storage/' . $storedPath;
                }
            }

            // Upload logo (image) and convert to WebP
            if ($request->hasFile('logo_url')) {
                $logo = $request->file('logo_url');
                $extension = strtolower($logo->getClientOriginalExtension());
                $logoName = time() . '_logo.webp';
                
                $sourcePath = $logo->getRealPath();
                $image = null;
                
                if ($extension == 'jpeg' || $extension == 'jpg') {
                    $image = @imagecreatefromjpeg($sourcePath);
                } elseif ($extension == 'png') {
                    $image = @imagecreatefrompng($sourcePath);
                    if ($image) {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                    }
                }
                
                $logoPath = $directoryPath . '/' . $logoName;
                $absolutePath = storage_path('app/public/' . $directoryPath);
                
                if (!file_exists($absolutePath)) {
                    mkdir($absolutePath, 0755, true);
                }
                
                if ($image && function_exists('imagewebp')) {
                    imagewebp($image, storage_path('app/public/' . $logoPath), 80);
                    imagedestroy($image);
                    $files['logo_url'] = '/storage/' . $logoPath;
                } else {
                    // Fallback to original
                    $fallbackName = time() . '_logo.' . $extension;
                    $fallbackPath = $logo->storeAs($directoryPath, $fallbackName, 'public');
                    $files['logo_url'] = '/storage/' . $fallbackPath;
                }
            }

            // Upload kalender_akademik (PDF → store directly, Image → convert to WebP)
            if ($request->hasFile('kalender_akademik')) {
                $kalender = $request->file('kalender_akademik');
                $kalenderExtension = strtolower($kalender->getClientOriginalExtension());

                if ($kalenderExtension === 'pdf') {
                    $kalenderName = time() . '_kalender-akademik.pdf';
                    $storedPath = $kalender->storeAs($directoryPath, $kalenderName, 'public');
                    $files['kalender_akademik'] = '/storage/' . $storedPath;
                } else {
                    // Image (JPG/PNG) → convert to WebP
                    $kalenderName = time() . '_kalender-akademik.webp';
                    $sourcePath = $kalender->getRealPath();
                    $image = null;

                    if ($kalenderExtension === 'jpeg' || $kalenderExtension === 'jpg') {
                        $image = @imagecreatefromjpeg($sourcePath);
                    } elseif ($kalenderExtension === 'png') {
                        $image = @imagecreatefrompng($sourcePath);
                        if ($image) {
                            imagepalettetotruecolor($image);
                            imagealphablending($image, true);
                            imagesavealpha($image, true);
                        }
                    }

                    $kalenderPath = $directoryPath . '/' . $kalenderName;
                    $absolutePath = storage_path('app/public/' . $directoryPath);

                    if (!file_exists($absolutePath)) {
                        mkdir($absolutePath, 0755, true);
                    }

                    if ($image && function_exists('imagewebp')) {
                        imagewebp($image, storage_path('app/public/' . $kalenderPath), 80);
                        imagedestroy($image);
                        $files['kalender_akademik'] = '/storage/' . $kalenderPath;
                    } else {
                        // Fallback to original format
                        $fallbackName = time() . '_kalender-akademik.' . $kalenderExtension;
                        $fallbackPath = $kalender->storeAs($directoryPath, $fallbackName, 'public');
                        $files['kalender_akademik'] = '/storage/' . $fallbackPath;
                    }
                }
            }

            // Update or create Institusi
            $this->institusiRepository->update($assessment->institution_id, [
                'nama_institusi' => $validated['nama_pt'],
                'jenis_institusi' => $validated['jenis_pt'],
                'logo_url' => $files['logo_url'] ?? null, // Will be ignored if null in update
            ]);

            // Cari ID reviewer tester
            $testerReviewer = $this->userRepository->findByEmail('reviewer@admin.com');
            
            // Update Assessment status to ACTIVE after verification
            $this->assessmentRepository->update($assessment->id, [
                'nama_pic' => $validated['nama_pic'],
                'jabatan_pic' => $validated['jabatan_pic'],
                'no_hp_pic' => $validated['no_hp_pic'],
                'status' => 'ACTIVE',
                'reviewer_id' => $testerReviewer ? $testerReviewer->id : null,
            ]);

            // Create or update Identitas
            $identitas = $this->assessmentRepository->upsertIdentitas($assessment->id, [
                'visi' => $validated['visi'],
                'misi' => $validated['misi'],
                'jml_mahasiswa' => $validated['jumlah_mahasiswa'],
                'jml_dosen' => $validated['jumlah_dosen'],
                'jml_tendik' => $validated['jumlah_tendik'],
                'jml_prodi' => $validated['jumlah_prodi'],
                'jml_fakultas' => $validated['jumlah_fakultas'],
                'jml_ukm' => $validated['jumlah_ukm'],
                'jml_ormawa' => $validated['jumlah_ormawa'],
                'legal_documents' => $files,
                'is_verified' => false,
            ]);

            // Upsert Agama data
            $agamaData = [
                'islam' => $validated['agama_islam'],
                'kristen' => $validated['agama_kristen'],
                'katolik' => $validated['agama_katolik'],
                'hindu' => $validated['agama_hindu'],
                'buddha' => $validated['agama_buddha'],
                'konghucu' => $validated['agama_konghucu'],
            ];

            foreach ($agamaData as $namaAgama => $jumlah) {
                $this->assessmentRepository->upsertAgama($identitas->id, $namaAgama, $jumlah);
            }

            return $this->successResponse(null, 'Verifikasi berhasil dikirim', 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validasi gagal.', 422, $e->errors());
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
}
