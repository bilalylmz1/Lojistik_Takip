<div>
    <x-layouts::app :title="__('Mal Kabul — İrsaliyeler')">

        {{-- Başlık --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <flux:heading size="xl">Mal Kabul (Inbound)</flux:heading>
                <flux:subheading>Gelen irsaliyeleri yönetin ve stok girişi yapın.</flux:subheading>
            </div>
            <flux:button variant="primary" :href="route('inbound.create')" wire:navigate icon="plus">
                Yeni İrsaliye
            </flux:button>
        </div>

        {{-- Flash Mesaj --}}
        @if (session()->has('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-zinc-800 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtreler --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-4">
            <div class="flex-1">
                <flux:input wire:model.live.debounce.300ms="search"
                    placeholder="İrsaliye no ile ara..."
                    icon="magnifying-glass" />
            </div>
            <div class="w-full sm:w-48">
                <select wire:model.live="statusFilter"
                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm text-sm">
                    <option value="">Tüm Durumlar</option>
                    <option value="pending">Bekliyor</option>
                    <option value="processing">İşlemde</option>
                    <option value="completed">Tamamlandı</option>
                </select>
            </div>
        </div>

        {{-- Tablo --}}
        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">İrsaliye No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Kalem Sayısı</th>
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
                                @php
                                    $badge = match($order->status) {
                                        'pending'    => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'completed'  => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                        default      => 'bg-zinc-100 text-zinc-700',
                                    };
                                    $label = match($order->status) {
                                        'pending'    => 'Bekliyor',
                                        'processing' => 'İşlemde',
                                        'completed'  => 'Tamamlandı',
                                        default      => $order->status,
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $order->items_count }} kalem
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-1">
                                @if ($order->status === 'pending')
                                    <flux:button variant="ghost" size="sm"
                                        :href="route('inbound.receive', $order->id)"
                                        wire:navigate>
                                        Teslim Al
                                    </flux:button>
                                @elseif ($order->status === 'processing')
                                    <flux:button variant="ghost" size="sm"
                                        :href="route('inbound.putaway', $order->id)"
                                        wire:navigate>
                                        Yerleştir
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span>Kayıtlı irsaliye bulunamadı.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>

    </x-layouts::app>
</div>
