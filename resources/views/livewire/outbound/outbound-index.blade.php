<div>
    <x-layouts::app :title="__('Sipariş Yönetimi — Outbound')">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <flux:heading size="xl">Sipariş Toplama (Outbound)</flux:heading>
                <flux:subheading>Sevkiyat siparişlerini oluşturun ve takip edin.</flux:subheading>
            </div>
            <flux:button variant="primary" :href="route('outbound.create')" wire:navigate icon="plus">
                Yeni Sipariş
            </flux:button>
        </div>

        @if (session()->has('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-zinc-800 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtreler --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-4">
            <div class="flex-1">
                <flux:input wire:model.live.debounce.300ms="search"
                    placeholder="Sipariş no ile ara..."
                    icon="magnifying-glass" />
            </div>
            <div class="w-full sm:w-48">
                <select wire:model.live="statusFilter"
                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm text-sm">
                    <option value="">Tüm Durumlar</option>
                    <option value="pending">Bekliyor</option>
                    <option value="picking">Toplanıyor</option>
                    <option value="ready">Hazır</option>
                    <option value="shipped">Sevk Edildi</option>
                </select>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Sipariş No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Kalem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Oluşturulma</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->statusBadgeClass() }}">
                                    {{ $order->statusLabelTr() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $order->items_count }} kalem
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-1">
                                @if (in_array($order->status, ['pending', 'picking']))
                                    <flux:button variant="ghost" size="sm"
                                        :href="route('outbound.picklist', $order->id)"
                                        wire:navigate>
                                        Pick List
                                    </flux:button>
                                @elseif ($order->status === 'ready')
                                    <flux:button variant="primary" size="sm"
                                        :href="route('outbound.ship', $order->id)"
                                        wire:navigate>
                                        Sevk Et
                                    </flux:button>
                                @else
                                    <span class="text-xs text-zinc-400">Tamamlandı</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-8 h-8 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    <span>Kayıtlı sipariş bulunamadı.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $orders->links() }}</div>

    </x-layouts::app>
</div>
