<x-filament-panels::page>
    <div class="flex flex-col lg:flex-row gap-6" x-data="{ showPreview: @entangle('showPreview') }">
        {{-- Form Panel --}}
        <div :class="showPreview ? 'lg:w-1/2' : 'w-full'" class="transition-all">
            {{-- Tab Navigation --}}
            <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
                @foreach(['welcome', 'profile', 'visi-misi', 'tim', 'penghargaan', 'panduan', 'pengumuman'] as $page)
                    <button
                        wire:click="$set('activeTab', '{{ $page }}')"
                        type="button"
                        @class([
                            'px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors',
                            'bg-primary-500 text-white' => $activeTab === $page,
                            'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' => $activeTab !== $page,
                        ])
                    >
                        {{ ucfirst(str_replace('-', ' ', $page)) }}
                    </button>
                @endforeach
            </div>

            {{-- Form Content --}}
            <form wire:submit="save">
                {{ $this->form }}

                <div class="mt-4 flex items-center gap-3">
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                        Simpan
                    </x-filament::button>

                    <span wire:loading wire:target="save" class="text-sm text-gray-500">
                        Menyimpan...
                    </span>
                </div>
            </form>
        </div>

        {{-- Preview Panel --}}
        <div x-show="showPreview" x-transition class="lg:w-1/2">
            <div class="sticky top-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Preview</h3>
                    <button @click="showPreview = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>
                <iframe
                    id="preview-frame"
                    src="{{ \Illuminate\Support\Facades\Route::has('compro.preview') ? route('compro.preview', ['page' => $activeTab]) : '#' }}"
                    class="w-full h-[600px] border rounded-lg bg-white"
                    wire:key="preview-{{ $activeTab }}"
                ></iframe>
            </div>
        </div>

        {{-- Toggle Preview Button (when hidden) --}}
        <button
            x-show="!showPreview"
            @click="showPreview = true"
            type="button"
            class="fixed bottom-4 right-4 bg-primary-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-primary-600 transition-colors z-50"
        >
            Tampilkan Preview
        </button>
    </div>

    @script
    <script>
        $wire.on('content-saved', () => {
            const iframe = document.getElementById('preview-frame');
            if (iframe) {
                iframe.src = iframe.src;
            }
        });
    </script>
    @endscript
</x-filament-panels::page>
