<div>
<div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-1">
                <a href="{{ route('outbound.index') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-200">Sipariş Toplama</a>
                <span>/</span>
                <span>Yeni Sipariş</span>
            </div>
            <flux:heading size="xl">Yeni Sipariş Oluştur</flux:heading>
            <flux:subheading>Sevkiyat siparişini ve ürün kalemlerini tanımlayın.</flux:subheading>
        </div>

        @if ($errors->has('stock'))
            <div class="mb-4 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 text-sm text-red-700 dark:text-red-300 space-y-1">
                <p class="font-semibold mb-1">Yetersiz Stok:</p>
                @foreach ($errors->get('stock') as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form wire:submit="save" class="space-y-6 max-w-3xl">

            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Sipariş Kalemleri</h3>
                    <flux:button type="button" variant="ghost" size="sm" wire:click="addItem" icon="plus">
                        Kalem Ekle
                    </flux:button>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($items as $index => $item)
                        <div class="px-6 py-4 flex items-start gap-4" wire:key="item-{{ $index }}">

                            {{-- Ürün Seçimi --}}
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-zinc-500 mb-1">Ürün</label>
                                <select wire:model.live="items.{{ $index }}.product_id"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm text-sm">
                                    <option value="">-- Ürün Seçiniz --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            @if($product->available_stock === 0) disabled @endif>
                                            {{ $product->name }}
                                            ({{ $product->sku }})
                                            — Stok: {{ $product->available_stock }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("items.{$index}.product_id")
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Miktar --}}
                            <div class="w-32">
                                <label class="block text-xs font-medium text-zinc-500 mb-1">Miktar</label>
                                <input type="number"
                                    wire:model="items.{{ $index }}.quantity"
                                    min="1"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm text-sm" />
                                @error("items.{$index}.quantity")
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kaldır --}}
                            <div class="pt-5">
                                @if (count($items) > 1)
                                    <button type="button" wire:click="removeItem({{ $index }})"
                                        class="text-red-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-zinc-500">Henüz kalem eklenmedi.</div>
                    @endforelse
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" :href="route('outbound.index')" wire:navigate>İptal</flux:button>
                <flux:button type="submit" variant="primary">Siparişi Oluştur</flux:button>
            </div>

        </form>


</div>
