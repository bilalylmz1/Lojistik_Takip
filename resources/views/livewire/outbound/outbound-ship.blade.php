<div>
    <x-layouts::app :title="'Sevkiyat Onayı — ' . $order->order_number">

        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-1">
                <a href="{{ route('outbound.index') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-200">Sipariş Toplama</a>
                <span>/</span>
                <span>Sevkiyat Onayı</span>
            </div>
            <flux:heading size="xl">Sevkiyat Onayı</flux:heading>
            <flux:subheading>Siparişi kontrol edin ve sevkiyatı onaylayın.</flux:subheading>
        </div>

        @if (session()->has('error'))
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-zinc-800 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-2xl space-y-5">

            {{-- Sipariş Bilgileri --}}
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-5 border-l-4 border-purple-500">
                <p class="text-xs text-zinc-500 uppercase tracking-wide mb-3">Sipariş Bilgileri</p>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-zinc-500">Sipariş No</p>
                        <p class="font-mono font-semibold text-zinc-900 dark:text-zinc-100 mt-0.5">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500">Durum</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-0.5 {{ $order->statusBadgeClass() }}">
                            {{ $order->statusLabelTr() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-zinc-500">Toplam Kalem</p>
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100 mt-0.5">{{ $order->items->count() }} kalem</p>
                    </div>
                </div>
            </div>

            {{-- Kalem Özeti --}}
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800">
                    <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Toplanan Ürünler</p>
                </div>
                <table class="min-w-full divide-y divide-zinc-100 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/40">
                        <tr>
                            <th class="px-5 py-2 text-left text-xs font-medium text-zinc-500 uppercase">Ürün</th>
                            <th class="px-5 py-2 text-center text-xs font-medium text-zinc-500 uppercase">İstenen</th>
                            <th class="px-5 py-2 text-center text-xs font-medium text-zinc-500 uppercase">Toplanan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-5 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                    <div>{{ $item->product->name }}</div>
                                    <div class="text-xs text-zinc-500 font-mono">{{ $item->product->sku }}</div>
                                </td>
                                <td class="px-5 py-3 text-center text-sm text-zinc-700 dark:text-zinc-300">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="font-semibold text-sm {{ $item->isFullyPicked() ? 'text-green-600 dark:text-green-400' : 'text-red-500' }}">
                                        {{ $item->picked_quantity }}
                                        @if(!$item->isFullyPicked())
                                            <span class="text-xs font-normal">(eksik)</span>
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Onay Butonu --}}
            @if ($order->status === 'ready')
                <div class="flex items-start gap-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-sm text-green-700 dark:text-green-300">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Tüm kalemler toplandı. Siparişi sevke onaylamak için butona tıklayın.</span>
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button variant="ghost" :href="route('outbound.index')" wire:navigate>Geri</flux:button>
                    <flux:button variant="primary"
                        wire:click="confirmShipment"
                        wire:confirm="Bu siparişi sevk etmek istediğinize emin misiniz?"
                        wire:loading.attr="disabled">
                        ✓ Sevkiyatı Onayla
                    </flux:button>
                </div>
            @else
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-sm text-yellow-700 dark:text-yellow-300">
                    Bu sipariş henüz "Hazır" durumunda değil. Önce Pick List adımını tamamlayın.
                </div>
                <div class="flex justify-end">
                    <flux:button variant="ghost" :href="route('outbound.index')" wire:navigate>Geri Dön</flux:button>
                </div>
            @endif

        </div>

    </x-layouts::app>
</div>
