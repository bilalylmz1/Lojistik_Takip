<x-layouts::app :title="__('Operasyon Merkezi')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <flux:heading size="xl">Operasyon Merkezi</flux:heading>
                <flux:subheading>Depo operasyonlarının güncel durumunu buradan takip edin.</flux:subheading>
            </div>
            <flux:text class="text-sm">{{ now()->format('d.m.Y') }}</flux:text>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm">Toplam Ürün</flux:text>
                <div class="mt-2 flex items-end justify-between gap-4">
                    <flux:heading size="xl">{{ number_format($productCount) }}</flux:heading>
                    <span class="rounded-lg bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">Master veri</span>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm">Depodaki Stok</flux:text>
                <div class="mt-2 flex items-end justify-between gap-4">
                    <flux:heading size="xl">{{ number_format($stockQuantity) }}</flux:heading>
                    <span class="rounded-lg bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">Adet</span>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm">Bugünkü Mal Kabul</flux:text>
                <div class="mt-2 flex items-end justify-between gap-4">
                    <flux:heading size="xl">{{ number_format($inboundToday) }}</flux:heading>
                    <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">İrsaliye</span>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:text class="text-sm">Bugünkü Sevkiyat</flux:text>
                <div class="mt-2 flex items-end justify-between gap-4">
                    <flux:heading size="xl">{{ number_format($outboundToday) }}</flux:heading>
                    <span class="rounded-lg bg-violet-50 px-2.5 py-1 text-xs font-medium text-violet-700 dark:bg-violet-950/40 dark:text-violet-300">Sipariş</span>
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <flux:heading size="lg">Bekleyen İşler</flux:heading>
                        <flux:subheading>İşleme alınmayı bekleyen operasyonlar.</flux:subheading>
                    </div>
                    <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">{{ $pendingInboundCount + $pendingOutboundCount }}</span>
                </div>

                <div class="mt-5 divide-y divide-zinc-100 dark:divide-zinc-800">
                    <a href="{{ route('inbound.index') }}" wire:navigate class="flex items-center justify-between py-3 first:pt-0 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <div>
                            <div class="font-medium">Mal Kabul</div>
                            <flux:text class="text-sm">Bekleyen veya işlenmekte olan irsaliyeler</flux:text>
                        </div>
                        <span class="font-semibold">{{ $pendingInboundCount }}</span>
                    </a>
                    <a href="{{ route('outbound.index') }}" wire:navigate class="flex items-center justify-between py-3 last:pb-0 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <div>
                            <div class="font-medium">Sipariş Toplama</div>
                            <flux:text class="text-sm">Toplama ve sevkiyat bekleyen siparişler</flux:text>
                        </div>
                        <span class="font-semibold">{{ $pendingOutboundCount }}</span>
                    </a>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <div>
                    <flux:heading size="lg">Hızlı İşlemler</flux:heading>
                    <flux:subheading>Günlük operasyonlara doğrudan erişin.</flux:subheading>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <flux:button variant="primary" :href="route('inbound.create')" wire:navigate icon="arrow-down-tray">Mal Kabul Oluştur</flux:button>
                    <flux:button variant="filled" :href="route('outbound.create')" wire:navigate icon="arrow-up-tray">Sevkiyat Siparişi</flux:button>
                    <flux:button variant="ghost" :href="route('stock.index')" wire:navigate icon="squares-2x2">Stokları Görüntüle</flux:button>
                    <flux:button variant="ghost" :href="route('products.index')" wire:navigate icon="cube">Ürünleri Yönet</flux:button>
                </div>

                <div class="mt-5 rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm dark:border-zinc-700 dark:bg-zinc-800/60">
                    <span class="font-medium {{ $lowStockCount > 0 ? 'text-red-700 dark:text-red-300' : 'text-zinc-700 dark:text-zinc-200' }}">Düşük stok uyarısı:</span>
                    <span class="text-zinc-600 dark:text-zinc-300">{{ $lowStockCount > 0 ? $lowStockCount.' stok kaydı kritik seviyede.' : 'Kritik seviyede stok kaydı bulunmuyor.' }}</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>