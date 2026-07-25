<div>
    <x-layouts::app :title="$product && $product->exists ? 'Ürün Düzenle' : 'Yeni Ürün Ekle'">
        <div class="mb-6">
            <flux:heading size="xl">{{ $product && $product->exists ? 'Ürün Düzenle' : 'Yeni Ürün Ekle' }}</flux:heading>
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-6 max-w-2xl">
            <form wire:submit="save" class="space-y-6">
                
                <div>
                    <flux:input wire:model="sku" label="SKU (Stok Kodu)" required />
                </div>
                
                <div>
                    <flux:input wire:model="barcode" label="Barkod" />
                </div>

                <div>
                    <flux:input wire:model="name" label="Ürün Adı" required />
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <flux:button variant="ghost" :href="route('products.index')" wire:navigate>İptal</flux:button>
                    <flux:button type="submit" variant="primary">Kaydet</flux:button>
                </div>
                
            </form>
        </div>
    </x-layouts::app>
</div>