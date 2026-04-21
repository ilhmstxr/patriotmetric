<x-layouts.dashboard>
    <x-slot:title>Data Profil</x-slot:title>

    <div class="bg-[#f5f5f5] min-h-full py-5 px-4 md:px-8">
        <div class="max-w-[860px] mx-auto space-y-5">

            {{-- ✏️ Badge periode + tombol Edit Profil --}}
            <x-dashboard.profil.periode-bar />

            {{-- ✏️ Section Visi & Misi → components/dashboard/profil/visi-misi.blade.php --}}
            <x-dashboard.profil.visi-misi />

            {{-- ✏️ Section Data Institusi → components/dashboard/profil/institusi.blade.php --}}
            <x-dashboard.profil.institusi />

            {{-- ✏️ Section Data SDM → components/dashboard/profil/sdm.blade.php --}}
            <x-dashboard.profil.sdm />

            {{-- ✏️ Section Data Mahasiswa → components/dashboard/profil/mahasiswa.blade.php --}}
            <x-dashboard.profil.mahasiswa />

            {{-- ✏️ Section Demografi Agama → components/dashboard/profil/demografi.blade.php --}}
            <x-dashboard.profil.demografi />

            {{-- ✏️ Section Data PIC → components/dashboard/profil/pic.blade.php --}}
            <x-dashboard.profil.pic />

        </div>
    </div>
</x-layouts.dashboard>
