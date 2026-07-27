<div>
    <x-layouts::app :title="'Stok Düzelt — ' . $stock->product->name">

        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-1">
                <a href="{{ route('stock.index') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-200">Stok Takip</a>
                <span>/</span>
                <span>Manuel Düzeltme</span>
            </div>
            <flux:heading size="xl">Manuel Stok Düzeltme</flux:heading>
            <flux:subheading>Sayım farkı, hasar veya iade gibi durumlarda stok miktarını düzeltin.</flux:subheading>
        </div>

        <div class="max-w-xl space-y-6">

            {{-- Mevcut Stok Bilgisi --}}
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-5 border-l-4 border-zinc-400">
                <p class="text-xs text-zinc-500 uppercase tracking-wide mb-3">Mevcut Stok Durumu</p>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-zinc-500">Ürün</p>
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100 mt-0.5">{{ $stock->product->name }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500">SKU</p>
                        <p class="font-mono text-zinc-700 dark:text-zinc-300 mt-0.5">{{ $stock->product->sku }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500">Lokasyon</p>
                        <p class="font-mono font-semibold text-zinc-900 dark:text-zinc-100 mt-0.5">{{ $stock->location->code }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center gap-2">
                    <span class="text-zinc-500 text-sm">Mevcut Miktar:</span>
                    <span class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stock->quantity }}</span>
                    <span class="text-zinc-500 text-sm">adet</span>
                </div>
            </div>

            {{-- Düzeltme Formu --}}
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-5">
                <form wire:submit="save" class="space-y-5">

                    {{-- Artır / Azalt Seçimi --}}
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">İşlem Türü</label>
                        <div class="flex rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden text-sm font-medium">
                            <button type="button"
                                wire:click="$set('adjustType', 'add')"
                                class="flex-1 py-2.5 transition-colors flex items-center justify-center gap-2
                                    {{ $adjustType === 'add' ? 'bg-green-600 text-white' : 'bg-white text-zinc-600 hover:bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Stok Ekle
                            </button>
                            <button type="button"
                                wire:click="$set('adjustType', 'subtract')"
                                class="flex-1 py-2.5 border-l border-zinc-200 dark:border-zinc-700 transition-colors flex items-center justify-center gap-2
                                    {{ $adjustType === 'subtract' ? 'bg-red-600 text-white' : 'bg-white text-zinc-600 hover:bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                                Stok Düş
                            </button>
                        </div>
                    </div>

                    {{-- Miktar --}}
                    <div>
                        <flux:input type="number" wire:model="amount" label="Miktar" min="1" required />
                        @error('amount')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sebep --}}
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                            Düzeltme Sebebi <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="reason"
                            class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm text-sm">
                            <option value="">-- Sebep Seçiniz --</option>
                            <option value="Fiziksel sayım farkı">Fiziksel sayım farkı</option>
                            <option value="Hasar/fire">Hasar / Fire</option>
                            <option value="İade alındı">İade alındı</option>
                            <option value="Sistem hatası düzeltme">Sistem hatası düzeltme</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                        @error('reason')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Önizleme --}}
                    @if ($amount > 0)
                        <div class="p-3 rounded-lg {{ $adjustType === 'add' ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }} text-sm">
                            <span class="{{ $adjustType === 'add' ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                {{ $stock->quantity }} →
                                <strong>{{ $adjustType === 'add' ? $stock->quantity + $amount : max(0, $stock->quantity - $amount) }}</strong>
                                adet
                                ({{ $adjustType === 'add' ? '+' : '-' }}{{ $amount }})
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-2">
                        <flux:button variant="ghost" :href="route('stock.index')" wire:navigate>İptal</flux:button>
                        <flux:button type="submit" variant="primary">Düzeltmeyi Kaydet</flux:button>
                    </div>

                </form>
            </div>

        </div>

    </x-layouts::app>
</div>
