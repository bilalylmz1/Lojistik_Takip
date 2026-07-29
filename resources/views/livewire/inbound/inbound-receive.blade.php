<div>
order_number">

        {{-- Başlık --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-1">
                <a href="{{ route('inbound.index') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-200">Mal Kabul</a>
                <span>/</span>
                <span>Teslim Al</span>
            </div>
            <flux:heading size="xl">Teslim Al — {{ $order->order_number }}</flux:heading>
            <flux:subheading>Her kalem için fiilen teslim alınan miktarı giriniz.</flux:subheading>
        </div>

        <form wire:submit="save" class="max-w-3xl space-y-6">

            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Ürün</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">Beklenen</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">Alınan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach ($order->items as $item)
                            <tr wire:key="item-{{ $item->id }}">
                                <td class="px-6 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $item->product->name }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-zinc-500 dark:text-zinc-400">
                                    {{ $item->product->sku }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-7 rounded bg-zinc-100 dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                        {{ $item->expected_quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input
                                        type="number"
                                        wire:model="receivedQtys.{{ $item->id }}"
                                        min="0"
                                        class="w-20 text-center rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm text-sm"
                                    />
                                    @error("receivedQtys.{$item->id}")
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Bilgi Notu --}}
            <div class="flex items-start gap-2 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-sm text-blue-700 dark:text-blue-300">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Kaydet ve Devam Et'e tıkladığınızda lokasyona yerleştirme (put-away) adımına geçeceksiniz.</span>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" :href="route('inbound.index')" wire:navigate>
                    Geri
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Kaydet ve Devam Et →
                </flux:button>
            </div>

        </form>


</div>
