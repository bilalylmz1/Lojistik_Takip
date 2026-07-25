<div>
    <x-layouts::app :title="__('Lokasyon Yönetimi')">
        <div class="flex justify-between items-center mb-6">
            <flux:heading size="xl">Lokasyonlar</flux:heading>
            <flux:button variant="primary" :href="route('locations.create')" wire:navigate>
                Yeni Lokasyon Ekle
            </flux:button>
        </div>

        @if (session()->has('error'))
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-zinc-800 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-4">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Lokasyon Kodu veya Adı ile ara..." icon="magnifying-glass" />
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Kod</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Tür</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Adı</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Üst Lokasyon</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse ($locations as $location)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $location->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ ucfirst($location->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $location->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $location->parent ? $location->parent->code : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:button variant="ghost" size="sm" :href="route('locations.edit', $location->id)" wire:navigate>Düzenle</flux:button>
                                <flux:button variant="danger" size="sm" wire:click="deleteLocation({{ $location->id }})" wire:confirm="Bu lokasyonu silmek istediğinize emin misiniz?">Sil</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 text-center">Kayıtlı lokasyon bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $locations->links() }}
        </div>
    </x-layouts::app>
</div>