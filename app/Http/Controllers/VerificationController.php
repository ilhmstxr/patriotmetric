<?php

namespace App\Http\Controllers;

use App\Models\Institusi;
use App\Models\Identitas;
use App\Models\Pengumpulan;
use App\Models\Agama;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    use ApiResponse;

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
            $pengumpulan = Pengumpulan::where('user_id', $userId)->first();

            if (!$pengumpulan) {
                return $this->errorResponse('Data pengumpulan tidak ditemukan. Silakan register terlebih dahulu.', 404);
            }

            // Validate all inputs — max 5MB (5120 KB) per file
            $validated = $request->validate([
                // Files — Section 1: Dokumen Legal
                'surat_pernyataan' => 'required|file|mimes:pdf|max:5120',
                'sk_pendirian' => 'required|file|mimes:pdf|max:5120',
                'sk_akreditasi' => 'required|file|mimes:pdf|max:5120',
                // Files — Section 2: Berkas Profil
                'profil_pt' => 'required|file|mimes:pdf|max:5120',
                'logo_pt' => 'required|file|mimes:jpeg,jpg,png|max:5120',
                'struktur_organisasi' => 'required|file|mimes:pdf|max:5120',
                'sk_tim' => 'required|file|mimes:pdf|max:5120',
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
                'agama_kepercayaan' => 'required|integer|min:0',
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
            $pdfFields = ['surat_pernyataan', 'sk_pendirian', 'sk_akreditasi', 'profil_pt', 'struktur_organisasi', 'sk_tim'];

            foreach ($pdfFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $safeFileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
                    $storedPath = $file->storeAs($directoryPath, $safeFileName, 'public');
                    $files[$field] = '/storage/' . $storedPath;
                }
            }

            // Upload logo (image) and convert to WebP
            if ($request->hasFile('logo_pt')) {
                $logo = $request->file('logo_pt');
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
                    $files['logo_pt'] = '/storage/' . $logoPath;
                } else {
                    // Fallback to original
                    $fallbackName = time() . '_logo.' . $extension;
                    $fallbackPath = $logo->storeAs($directoryPath, $fallbackName, 'public');
                    $files['logo_pt'] = '/storage/' . $fallbackPath;
                }
            }

            // Update or create Institusi
            $institusi = Institusi::find($pengumpulan->institution_id);
            if ($institusi) {
                $institusi->update([
                    'nama_institusi' => $validated['nama_pt'],
                    'jenis_institusi' => $validated['jenis_pt'],
                    'logo_url' => $files['logo_pt'] ?? $institusi->logo_url,
                ]);
            }

            // Cari ID reviewer tester
            $testerReviewer = \App\Models\User::where('email', 'reviewer@admin.com')->first();
            
            // Update Pengumpulan with PIC data
            $pengumpulan->update([
                'nama_pic' => $validated['nama_pic'],
                'jabatan_pic' => $validated['jabatan_pic'],
                'no_hp_pic' => $validated['no_hp_pic'],
                'status' => 'IN_PROGRESS',
                'reviewer_id' => $testerReviewer ? $testerReviewer->id : null,
            ]);

            // Update User status to IN_PROGRESS
            $user = \App\Models\User::find($userId);
            if ($user) {
                $user->update(['status' => 'IN_PROGRESS']);
            }

            // Create or update Identitas
            $identitas = Identitas::updateOrCreate(
                ['pengumpulan_id' => $pengumpulan->id],
                [
                    'visi' => $validated['visi'],
                    'misi' => $validated['misi'],
                    'jml_mahasiswa' => $validated['jumlah_mahasiswa'],
                    'jml_dosen' => $validated['jumlah_dosen'],
                    'jml_tendik' => $validated['jumlah_tendik'],
                    'jml_prodi' => $validated['jumlah_prodi'],
                    'jml_ukm' => $validated['jumlah_ukm'],
                    'jml_ormawa' => $validated['jumlah_ormawa'],
                    'legal_documents' => json_encode($files),
                    'is_verified' => false,
                ]
            );

            // Upsert Agama data
            $agamaData = [
                'islam' => $validated['agama_islam'],
                'kristen' => $validated['agama_kristen'],
                'katolik' => $validated['agama_katolik'],
                'hindu' => $validated['agama_hindu'],
                'buddha' => $validated['agama_buddha'],
                'konghucu' => $validated['agama_konghucu'],
                'Kepercayaan Terhadap Tuhan Yang Maha Esa' => $validated['agama_kepercayaan'],
            ];

            foreach ($agamaData as $namaAgama => $jumlah) {
                Agama::updateOrCreate(
                    ['identitas_id' => $identitas->id, 'agama' => $namaAgama],
                    ['jumlah' => $jumlah]
                );
            }

            return $this->successResponse(null, 'Verifikasi berhasil dikirim', 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validasi gagal.', 422, $e->errors());
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
}
