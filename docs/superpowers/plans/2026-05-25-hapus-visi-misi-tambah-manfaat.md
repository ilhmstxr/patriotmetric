# Hapus Visi & Misi + Tambah Manfaat Pemeringkatan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menghapus halaman Visi & Misi dari admin CMS dan public site, serta menambahkan section "Manfaat Pemeringkatan" di halaman Profile.

**Architecture:** Menghapus file Filament page + form, route, view, navigation links, dan preview controller reference untuk visi-misi. Menambahkan static section baru di profile.blade.php setelah Tujuan Utama.

**Tech Stack:** Laravel, Filament, Blade, Tailwind CSS

---

## File Structure

**Files to delete:**
- `app/Filament/Pages/CmsComproVisiMisi.php`
- `app/Filament/Pages/ComproForms/VisiMisiForm.php`
- `resources/views/visi-misi.blade.php`
- `resources/views/compro-preview/visi-misi.blade.php`

**Files to modify:**
- `routes/web.php` — remove visi-misi route
- `resources/views/components/navbar.blade.php` — remove visi-misi links
- `resources/views/components/footer.blade.php` — remove visi-misi link
- `app/Http/Controllers/ComproPreviewController.php` — remove 'visi-misi' from validPages
- `resources/views/profile.blade.php` — add Manfaat Pemeringkatan section

---

### Task 1: Hapus Admin CMS Page Visi & Misi

**Files:**
- Delete: `app/Filament/Pages/CmsComproVisiMisi.php`
- Delete: `app/Filament/Pages/ComproForms/VisiMisiForm.php`

- [ ] **Step 1: Delete CmsComproVisiMisi.php**

```powershell
Remove-Item "app/Filament/Pages/CmsComproVisiMisi.php" -Force
```

- [ ] **Step 2: Delete VisiMisiForm.php**

```powershell
Remove-Item "app/Filament/Pages/ComproForms/VisiMisiForm.php" -Force
```

- [ ] **Step 3: Commit**

```powershell
git add -A
git commit -m "remove: hapus admin CMS page Visi & Misi"
```

---

### Task 2: Hapus Route dan View Visi & Misi

**Files:**
- Modify: `routes/web.php:32-34`
- Delete: `resources/views/visi-misi.blade.php`
- Delete: `resources/views/compro-preview/visi-misi.blade.php`
- Modify: `app/Http/Controllers/ComproPreviewController.php:21`

- [ ] **Step 1: Remove route dari web.php**

Hapus baris 32-34:
```php
Route::get('/visi-misi', function () {
    return view('visi-misi');
});
```

- [ ] **Step 2: Remove 'visi-misi' dari ComproPreviewController validPages**

Di `app/Http/Controllers/ComproPreviewController.php:21`, ubah:
```php
$validPages = ['welcome', 'profile', 'visi-misi', 'tim', 'penghargaan', 'panduan', 'pengumuman'];
```
Menjadi:
```php
$validPages = ['welcome', 'profile', 'tim', 'penghargaan', 'panduan', 'pengumuman'];
```

- [ ] **Step 3: Delete view files**

```powershell
Remove-Item "resources/views/visi-misi.blade.php" -Force
Remove-Item "resources/views/compro-preview/visi-misi.blade.php" -Force
```

- [ ] **Step 4: Commit**

```powershell
git add -A
git commit -m "remove: hapus route dan view visi-misi"
```

---

### Task 3: Hapus Link Visi & Misi dari Navigasi

**Files:**
- Modify: `resources/views/components/navbar.blade.php:64-66, 145`
- Modify: `resources/views/components/footer.blade.php:35-37`

- [ ] **Step 1: Remove link dari navbar desktop dropdown**

Di `resources/views/components/navbar.blade.php`, hapus baris 64-66:
```blade
                        <a href="{{ url('/visi-misi') }}" @click="dropdownOpen = false" class="block px-4 py-2.5 font-['Plus_Jakarta_Sans',sans-serif] text-[14px] transition-colors {{ request()->is('visi-misi') ? 'text-[#1B5E20] font-semibold bg-[#f0fdf4]' : 'text-[#45556c] hover:text-[#1B5E20] hover:bg-[#f8fafc]' }}">
                            Visi & Misi
                        </a>
```

- [ ] **Step 2: Remove link dari navbar mobile dropdown**

Di `resources/views/components/navbar.blade.php`, hapus baris 145:
```blade
                    <a href="{{ url('/visi-misi') }}" class="font-['Plus_Jakarta_Sans',sans-serif] py-1 text-[14px] font-medium text-[#45556c] hover:text-[#1b5e20]">Visi & Misi</a>
```

- [ ] **Step 3: Update active state check di navbar dropdown button**

Di `resources/views/components/navbar.blade.php` line 54, hapus `request()->is('visi-misi')` dari condition class pada button "Tentang Kami". Ubah:
```blade
{{ request()->is('profile') || request()->is('visi-misi') || request()->is('tim') ? 'font-semibold text-[#1B5E20]' : 'font-medium text-[#45556c] hover:text-[#1B5E20]' }}
```
Menjadi:
```blade
{{ request()->is('profile') || request()->is('tim') ? 'font-semibold text-[#1B5E20]' : 'font-medium text-[#45556c] hover:text-[#1B5E20]' }}
```

- [ ] **Step 4: Remove link dari footer**

Di `resources/views/components/footer.blade.php`, hapus baris 35-37:
```blade
                            <a href="{{ url('/visi-misi') }}" class="font-['Plus_Jakarta_Sans',sans-serif] font-normal leading-[20px] text-[13px] text-[#62748E] hover:text-[#1B5E20] hover:translate-x-1 transition-all">
                                Visi &amp; Misi
                            </a>
```

- [ ] **Step 5: Commit**

```powershell
git add -A
git commit -m "remove: hapus link visi-misi dari navbar dan footer"
```

---

### Task 4: Tambah Section Manfaat Pemeringkatan di Profile (Dinamis dari Database)

**Files:**
- Modify: `database/seeders/ComproContentSeeder.php` — tambah data manfaat-pemeringkatan
- Modify: `app/Filament/Pages/ComproForms/ProfileForm.php` — tambah section CMS form
- Modify: `resources/views/profile.blade.php` — render section dinamis

- [ ] **Step 1: Tambah seed data di ComproContentSeeder.php**

Di `database/seeders/ComproContentSeeder.php`, tambahkan di akhir method `seedProfilePage()` (setelah block tujuan-utama):

```php
        // Manfaat Pemeringkatan Section
        $this->createContent($page, 'manfaat-pemeringkatan', 'judul', 'text', 'Manfaat Pemeringkatan', 1);
        $this->createContent($page, 'manfaat-pemeringkatan', 'daftar', 'repeater', [
            ['nomor' => '01', 'judul' => 'Meningkatkan Kesadaran Bela Negara', 'deskripsi' => 'Mendorong Perguruan Tinggi untuk mewujudkan dan meningkatkan karakter bela negara.'],
            ['nomor' => '02', 'judul' => 'Membangun Jejaring dan Kolaborasi Nasional', 'deskripsi' => 'Membuka peluang bagi perguruan tinggi peserta menjadi bagian dari jejaring Patriot Metric yang memungkinkan kolaborasi di tingkat nasional.'],
            ['nomor' => '03', 'judul' => 'Mendapatkan Pengakuan dan Reputasi', 'deskripsi' => 'Meningkatkan citra dan reputasi perguruan tinggi sebagai kampus yang berkomitmen pada penguatan karakter bela negara.'],
            ['nomor' => '04', 'judul' => 'Mendorong Perubahan dan Aksi Sosial', 'deskripsi' => 'Perguruan tinggi dapat melakukan perubahan karakter dalam segala aspek Tridharma Perguruan Tinggi.'],
        ], 2);
```

- [ ] **Step 2: Tambah section form di ProfileForm.php**

Di `app/Filament/Pages/ComproForms/ProfileForm.php`, tambahkan section baru di akhir array `schema()` (setelah Section 'Tujuan Utama'):

```php
            Section::make('Manfaat Pemeringkatan')
                ->schema([
                    TextInput::make('manfaat-pemeringkatan.judul')->label('Judul Section')->maxLength(255)->required(),
                    Repeater::make('manfaat-pemeringkatan.daftar')
                        ->label('Daftar Manfaat')
                        ->schema([
                            TextInput::make('nomor')->label('Nomor')->maxLength(10)->required(),
                            TextInput::make('judul')->label('Judul')->maxLength(150)->required(),
                            Textarea::make('deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['nomor'] ?? '') . ' - ' . ($state['judul'] ?? 'Item Baru')),
                ]),
```

- [ ] **Step 3: Tambah variabel dan render section di profile.blade.php**

Di `resources/views/profile.blade.php`, tambahkan variabel di block `@php` (setelah baris `$tujuanDaftar`):

```php
    $manfaat = $content->get('manfaat-pemeringkatan', collect());
    $manfaatJudul = $manfaat->firstWhere('key', 'judul')?->value ?? 'Manfaat Pemeringkatan';
    $manfaatDaftar = $manfaat->firstWhere('key', 'daftar')?->value ?? [];
```

Kemudian tambahkan section setelah `@endif` Tujuan Utama (baris 83), sebelum closing `</div>`:

```blade
        {{-- Manfaat Pemeringkatan --}}
        @if(is_array($manfaatDaftar) && count($manfaatDaftar) > 0)
        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                <div class="text-center mb-12">
                    <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[26px] md:text-[32px] text-[#1d293d]">{{ $manfaatJudul }}</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($manfaatDaftar as $item)
                        <div class="bg-[#f8fafc] rounded-2xl border border-[#f1f5f9] p-7 hover:shadow-lg hover:border-[#1B5E20]/10 transition-all duration-300">
                            <div class="flex items-start gap-4">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] text-[#d4af37]/40 leading-none">{{ $item['nomor'] ?? '' }}</span>
                                <div>
                                    <h3 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[17px] text-[#1d293d]">{{ $item['judul'] ?? '' }}</h3>
                                    <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[24px] text-[#45556c]">{{ $item['deskripsi'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
```

- [ ] **Step 4: Run seeder untuk insert data**

```powershell
php artisan db:seed --class=ComproContentSeeder
```

- [ ] **Step 5: Verify tampilan**

Run: `php artisan serve` dan buka `/profile` di browser. Pastikan section Manfaat Pemeringkatan muncul setelah Tujuan Utama dengan 4 card yang datanya dari database. Cek juga admin CMS di `/admin/cms-compro/profile` bahwa section baru bisa diedit.

- [ ] **Step 6: Commit**

```powershell
git add -A
git commit -m "feat: tambah section Manfaat Pemeringkatan di halaman Profile (dinamis dari DB)"
```
