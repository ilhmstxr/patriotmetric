<x-layouts.app>
    <div class="min-h-[calc(100vh-65px)] bg-[#f8fafc] flex items-center justify-center py-20 px-4">
        <div class="max-w-2xl text-center">
            <h1 class="text-[120px] md:text-[180px] font-bold text-[#1b5e20] leading-none tracking-tighter opacity-20">404</h1>
            <h2 class="text-3xl md:text-5xl font-bold text-[#1d293d] mt-8 mb-4">Halaman Tidak Ditemukan</h2>
            <p class="text-[#64748b] text-lg md:text-xl max-w-lg mx-auto mb-10">
                Maaf, halaman yang Anda cari mungkin telah dihapus, namanya diubah, atau sementara tidak tersedia.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}" class="bg-[#1b5e20] hover:bg-[#15461c] text-white font-bold px-8 py-4 rounded-xl shadow-lg transition-all hover:-translate-y-1 w-full sm:w-auto">
                    Kembali ke Beranda
                </a>
                <button onclick="window.history.back()" class="bg-white border-2 border-[#e2e8f0] text-[#475569] hover:border-[#1b5e20] hover:text-[#1b5e20] font-bold px-8 py-4 rounded-xl transition-all w-full sm:w-auto">
                    Halaman Sebelumnya
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>
