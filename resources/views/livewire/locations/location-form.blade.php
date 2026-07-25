<div>
    <x-layouts::app :title="$location && $location->exists ? 'Lokasyon Düzenle' : 'Yeni Lokasyon Ekle'">
        <div class="mb-6">
            <flux:heading size="xl">{{ $location && $location->exists ? 'Lokasyon Düzenle' : 'Yeni Lokasyon Ekle' }}</flux:heading>
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-6 max-w-2xl">
            <form wire:submit="save" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Üst Lokasyon</label>
                    <select wire:model="parent_id" class="w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                        <option value="">-- Üst Lokasyon Yok (Ana Alan) --</option>
                        @foreach($parentLocations as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->code }} - {{ $parent->name }} ({{ ucfirst($parent->type) }})</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-zinc-500">Koridor eklerken bağlı olduğu alanı, raf eklerken bağlı olduğu koridoru seçiniz.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Lokasyon Türü <span class="text-red-500">*</span></label>
                    <select wire:model="type" required class="w-full border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                        <option value="alan">Alan</option>
                        <option value="koridor">Koridor</option>
                        <option value="raf">Raf</option>
                        <option value="goz">Göz</option>
                    </select>
                </div>

                <div>
                    <flux:input wire:model="code" label="Lokasyon Kodu (Örn: A1, K1, R1-G1)" required />
                </div>

                <div>
                    <flux:input wire:model="name" label="Lokasyon Adı" required />
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <flux:button variant="ghost" :href="route('locations.index')" wire:navigate>İptal</flux:button>
                    <flux:button type="submit" variant="primary">Kaydet</flux:button>
                </div>
                
            </form>
        </div>
    </x-layouts::app>
</div>