<div>
    <x-layouts::app :title="__('Stok Takip')">

        {{-- Başlık --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <flux:heading size="xl">Stok Takip</flux:heading>
                <flux:subheading>Depo genelindeki anlık stok durumunu izleyin.</flux:subheading>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-zinc-800 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtreler ve Görünüm Seçimi --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-4">
            <div class="flex-1">
                <flux:input wire:model.live.debounce.300ms="search"
                    placeholder="Ürün adı, SKU veya lokasyon kodu ile ara..."
                    icon="magnifying-glass" />
            </div>
            <div class="flex rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden text-sm font-medium">
                <button
                    wire:click="$set('view', 'location')"
                    class="px-4 py-2 transition-colors {{ $view === 'location' ? 'bg-zinc-800 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'bg-white text-zinc-600 hover:bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400' }}">
                    Lokasyon Bazlı
                </button>
                <button
                    wire:click="$set('view', 'product')"
                    class="px-4 py-2 border-l border-zinc-200 dark:border-zinc-700 transition-colors {{ $view === 'product' ? 'bg-zinc-800 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'bg-white text-zinc-600 hover:bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400' }}">
                    Ürün Bazlı
                </button>
            </div>
        </div>

        {{-- LOKASYON BAZLI TABLO --}}
        @if ($view === 'location')
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Lokasyon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Ürün</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">Miktar</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($stocks as $stock)
                            <tr>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-mono font-medium text-zinc-900 dark:text-zinc-100">{{ $stock->location->code }}</div>
                                    <div class="text-xs text-zinc-500">{{ $stock->location->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $stock->product->name }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-zinc-500 dark:text-zinc-400">
                                    {{ $stock->product->sku }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2.5 py-0.5 rounded-full text-sm font-semibold
                                        {{ $stock->quantity <= 5 ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                                        {{ $stock->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <flux:button variant="ghost" size="sm"
                                        :href="route('stock.adjust', $stock->id)"
                                        wire:navigate>
                                        Düzelt
                                    </flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>
                                        </svg>
                                        <span>Stok kaydı bulunamadı.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $stocks->links() }}</div>

        {{-- ÜRÜN BAZLI TABLO --}}
        @else
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Ürün</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">Toplam Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($productTotals as $row)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $row->product->name }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-zinc-500 dark:text-zinc-400">
                                    {{ $row->product->sku }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[3rem] px-3 py-1 rounded-full text-sm font-bold
                                        {{ $row->total_qty <= 10 ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                                        {{ $row->total_qty }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-sm text-zinc-500">Stok kaydı bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $productTotals->links() }}</div>
        @endif

    </x-layouts::app>
</div>
