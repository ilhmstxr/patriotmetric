<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patriot Metric - {{ $title ?? 'Dashboard' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.webp') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
    @livewireStyles
</head>
<body class="antialiased bg-[#f5f5f5]" style="font-family: 'Plus Jakarta Sans', sans-serif;"
      x-data
      x-init="$nextTick(() => { lucide.createIcons() })">

    {{-- Re-init Lucide setelah Livewire navigate --}}
    <script>
        document.addEventListener('livewire:navigated', () => {
            if (window.lucide) window.lucide.createIcons();
        });
    </script>

    {{-- ⚡ Immediate auth guard — redirect sebelum halaman render --}}
    <script>
        (function() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.replace('/masuk');
                return;
            }

            // Cek expiry token (dari "Ingat saya" / default)
            const expiresAt = localStorage.getItem('token_expires_at');
            if (expiresAt && Date.now() > new Date(expiresAt).getTime()) {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user');
                localStorage.removeItem('user_status');
                localStorage.removeItem('assessment_status');
                localStorage.removeItem('profile_data_cache');
                localStorage.removeItem('rubrik_questions_cache');
                localStorage.removeItem('token_expires_at');
                sessionStorage.clear();
                window.location.replace('/masuk');
                return;
            }
            
            // Sync check: Reviewer tidak boleh di dashboard peserta
            const userStr = localStorage.getItem('auth_user');
            if (userStr) {
                const user = JSON.parse(userStr);
                if (user.role === 'REVIEWER' || user.role === 'reviewer') {
                    window.location.replace('/reviewer');
                    return;
                }
                
                // Jika peserta tapi user status masih UNVERIFIED, lempar ke /cek-email
                // Jika assessment masih UNVERIFIED (explicitly set), lempar ke /verifikasi
                if (user.role === 'PESERTA' || user.role === 'peserta') {
                    const userStatus = localStorage.getItem('user_status');
                    if (!userStatus || userStatus === 'UNVERIFIED') {
                        window.location.replace('/cek-email');
                        return;
                    }
                    const assessmentStatus = localStorage.getItem('assessment_status');
                    if (assessmentStatus === 'UNVERIFIED') {
                        window.location.replace('/verifikasi');
                        return;
                    }
                }
            }
        })();
    </script>

    {{-- ============================================================ --}}
    {{-- HEADER: Bisa diedit di components/dashboard/header.blade.php --}}
    {{-- ============================================================ --}}
    @persist('dashboard-header')
    <header x-data="{ mobileMenuOpen: false, showBar: true, lastPos: 0, threshold: 50 }"
            @scroll.window="
                const cur = window.pageYOffset;
                if (cur < 10) { showBar = true; }
                else if (cur < lastPos - threshold) { showBar = true; }
                else if (cur > lastPos + 10) { showBar = false; }
                lastPos = cur;
            "
            class="bg-white fixed top-0 left-0 w-full z-50 transition-transform duration-300"
            :class="showBar ? 'translate-y-0' : '-translate-y-full'"
            style="box-shadow: 0 1px 0 #e0e0e0;">
        <x-dashboard.header />
        {{-- NAVBAR: Bisa diedit di components/dashboard/navbar.blade.php --}}
        <x-dashboard.navbar />
    </header>
    @endpersist

    {{-- Spacer to offset fixed header --}}
    <div class="h-[120px]"></div>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT                                                  --}}
    {{-- ============================================================ --}}
    <main class="min-h-[calc(100vh-120px)] bg-[#f5f5f5]">
        {{ $slot }}
    </main>

    {{-- Global Modals --}}
    <x-dashboard.password-modal />

    @livewireScripts
</body>
</html>
