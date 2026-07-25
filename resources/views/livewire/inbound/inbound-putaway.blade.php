<div>
    <x-layouts::app :title="'Put-away — ' . $order->order_number">

        {{-- Başlık --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-1">
                <a href="{{ route('inbound.index') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-200">Mal Kabul</a>
                <span>/</span>
                <span>Yerleştirme</span>
            </div>
            <flux:heading size="xl">Lokasyona Yerleştir — {{ $order->order_number }}</flux:heading>
            <flux:subheading>Her ürün için hedef lokasyonu (Göz) seçerek yerleştirin.</flux:subheading>
        </div>

        @if (session()->has('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-zinc-800 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Kalemlerin listesi --}}
        <div class="max-w-3xl space-y-4">

            @foreach ($order->items as $item)
                <div wire:key="putaway-{{ $item->id }}"
                    class="bg-white dark:bg-zinc-900 shadow rounded-lg p-5
                        @if($putawayDone[$item->id]) border-l-4 border-green-500 @endif">

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                        {{-- Ürün Bilgisi --}}
                        <div class="flex-1">
                            <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $item->product->name }}</p>
                            <p class="text-xs text-zinc-500 mt-0.5">SKU: {{ $item->product->sku }} — Teslim Alınan: <span class="font-semibold">{{ $item->received_quantity }}</span> adet</p>
                        </div>

                        @if ($putawayDone[$item->id])
                            {{-- Yerleştirildi --}}
                            <div class="flex items-center gap-2 text-green-600 dark:text-green-400 font-medium text-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Yerleştirildi
                            </div>
                        @else
                            {{-- Lokasyon Seçimi + Yerleştir Butonu --}}
                            <div class="flex items-end gap-2">
                                <div class="flex-1 sm:w-56">
                                    <label class="block text-xs font-medium text-zinc-500 mb-1">Hedef Lokasyon (Göz)</label>
                                    <select wire:model="locationSelections.{{ $item->id }}"
                                        class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm text-sm">
                                        <option value="">-- Lokasyon Seçin --</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->code }} — {{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                    @error("locationSelections.{$item->id}")
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <flux:button
                                    type="button"
                                    variant="primary"
                                    wire:click="putaway({{ $item->id }})"
                                    wire:loading.attr="disabled"
                                >
                                    Yerleştir
                                </flux:button>
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach

        </div>

        {{-- İlerleme --}}
        @php
            $total = count($putawayDone);
            $done  = count(array_filter($putawayDone));
        @endphp
        <div class="max-w-3xl mt-6 p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg flex items-center justify-between">
            <span class="text-sm text-zinc-600 dark:text-zinc-400">
                İlerleme: <strong>{{ $done }}</strong> / {{ $total }} kalem yerleştirildi
            </span>
            @if ($done === $total && $total > 0)
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">✓ Tüm kalemler tamamlandı</span>
            @endif
        </div>

    </x-layouts::app>
</div>
