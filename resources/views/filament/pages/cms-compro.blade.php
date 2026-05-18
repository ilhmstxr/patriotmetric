<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form wire:submit="save">
                {{ $this->form }}

                <div class="mt-6 flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                        Simpan Perubahan
                    </x-filament::button>

                    <span wire:loading wire:target="save" class="text-sm text-gray-500">
                        Menyimpan...
                    </span>
                </div>
            </form>
        </div>

        {{-- Preview Section --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-2">
                    <div class="flex gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="w-3 h-3 rounded-full bg-green-400"></span>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 font-mono">Preview — {{ ucfirst(str_replace('-', ' ', $this->getPreviewUrl())) }}</span>
                </div>
                <a href="{{ $this->getPreviewUrl() }}" target="_blank" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                    Buka di tab baru ↗
                </a>
            </div>
            {{-- 16:9 aspect ratio container --}}
            <div class="relative w-full" style="padding-bottom: 56.25%;">
                <iframe
                    id="preview-frame"
                    src="{{ $this->getPreviewUrl() }}"
                    class="absolute inset-0 w-full h-full"
                    wire:key="preview-frame"
                ></iframe>
            </div>
        </div>
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
