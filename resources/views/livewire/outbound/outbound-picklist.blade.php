<div>
    <x-layouts::app :title="'Pick List — ' . $order->order_number">

        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-1">
                <a href="{{ route('outbound.index') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-200">Sipariş Toplama</a>
                <span>/</span>
                <span>Pick List</span>
            </div>
            <flux:heading size="xl">Pick List — {{ $order->order_number }}</flux:heading>
            <flux:subheading>Aşağıdaki ürünleri gösterilen lokasyonlardan toplayın ve miktarları girin.</flux:subheading>
        </div>

        @if (session()->has('info'))
            <div class="mb-4 p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-zinc-800 dark:text-blue-400">
                {{ session('info') }}
            </div>
        @endif

        <form wire:submit="confirmPick" class="max-w-4xl space-y-4">

            @foreach ($order->items as $item)
                @php
                    $stockLocations = $this->getStockLocations($item->product_id);
                    $isPicked = ($pickedQtys[$item->id] ?? 0) >= $item->quantity;
                @endphp
                <div wire:key="pick-{{ $item->id }}"
                    class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden
                        {{ $isPicked ? 'border-l-4 border-green-500' : '' }}">

                    {{-- Ürün Başlık --}}
                    <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $item->product->name }}</p>
                            <p class="text-xs text-zinc-500 mt-0.5">SKU: {{ $item->product->sku }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-zinc-500">İstenen / Toplanan</p>
                            <p class="font-bold text-lg text-zinc-900 dark:text-zinc-100">
                                {{ $item->quantity }}
                                <span class="text-zinc-400">/</span>
                                <span class="{{ $isPicked ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $pickedQtys[$item->id] ?? 0 }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="px-5 py-4 flex flex-col sm:flex-row gap-6">

                        {{-- Stok Lokasyonları --}}
                        <div class="flex-1">
                            <p class="text-xs font-medium text-zinc-500 uppercase tracking-wide mb-2">Stok Lokasyonları</p>
                            @if ($stockLocations->isEmpty())
                                <p class="text-sm text-red-500">⚠ Bu ürün için stok bulunamadı!</p>
                            @else
                                <div class="space-y-1">
                                    @foreach ($stockLocations as $stockLoc)
                                        <div class="flex items-center justify-between text-sm py-1 px-2 rounded bg-zinc-50 dark:bg-zinc-800">
                                            <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ $stockLoc->location->code }}</span>
                                            <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $stockLoc->quantity }} adet</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Toplama Girişi --}}
                        <div class="flex items-end gap-3">
                            <div>
                                <label class="block text-xs font-medium text-zinc-500 mb-1">Toplanan Miktar</label>
                                <input type="number"
                                    wire:model="pickedQtys.{{ $item->id }}"
                                    min="0"
                                    max="{{ $item->quantity }}"
                                    class="w-24 text-center rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm text-sm" />
                                @error("pickedQtys.{$item->id}")
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            @if ($isPicked)
                                <div class="flex items-center gap-1 text-green-600 dark:text-green-400 font-medium text-sm pb-1">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tamamlandı
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach

            {{-- İlerleme --}}
            @php
                $totalItems = count($pickedQtys);
                $doneItems = collect($order->items)->filter(fn($i) => ($pickedQtys[$i->id] ?? 0) >= $i->quantity)->count();
            @endphp
            <div class="p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg flex items-center justify-between">
                <span class="text-sm text-zinc-600 dark:text-zinc-400">
                    İlerleme: <strong>{{ $doneItems }}</strong> / {{ $totalItems }} kalem tamamlandı
                </span>
                @if ($doneItems === $totalItems && $totalItems > 0)
                    <span class="text-sm text-green-600 font-medium">✓ Tüm kalemler hazır</span>
                @endif
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" :href="route('outbound.index')" wire:navigate>Geri</flux:button>
                <flux:button type="submit" variant="primary"
                    wire:loading.attr="disabled">
                    Toplamayı Onayla & Devam Et →
                </flux:button>
            </div>

        </form>

    </x-layouts::app>
</div>
