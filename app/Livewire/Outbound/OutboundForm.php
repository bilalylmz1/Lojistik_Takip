<?php

namespace App\Livewire\Outbound;

use App\Models\OutboundOrder;
use App\Models\Product;
use App\Models\Stock;
use Livewire\Component;

class OutboundForm extends Component
{
    public array $items = [];

    public function mount(): void
    {
        $this->addItem();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id' => '',
            'quantity'   => 1,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    protected function rules(): array
    {
        return [
            'items'                    => 'required|array|min:1',
            'items.*.product_id'       => 'required|exists:products,id',
            'items.*.quantity'         => 'required|integer|min:1',
        ];
    }

    protected function messages(): array
    {
        return [
            'items.*.product_id.required' => 'Her kalem için ürün seçilmelidir.',
            'items.*.quantity.min'        => 'Miktar en az 1 olmalıdır.',
        ];
    }

    /**
     * Ürün için toplam stok miktarını döndürür.
     */
    public function getAvailableStock(int $productId): int
    {
        return Stock::where('product_id', $productId)->sum('quantity');
    }

    public function save()
    {
        $this->validate();

        // Stok yeterliliği kontrolü
        $stockErrors = [];
        foreach ($this->items as $index => $item) {
            if (empty($item['product_id'])) continue;
            $available = $this->getAvailableStock((int) $item['product_id']);
            if ($item['quantity'] > $available) {
                $product = Product::find($item['product_id']);
                $stockErrors[] = "Kalem " . ($index + 1) . ": {$product->name} için yeterli stok yok. (Talep: {$item['quantity']}, Mevcut: {$available})";
            }
        }

        if (!empty($stockErrors)) {
            foreach ($stockErrors as $error) {
                $this->addError('stock', $error);
            }
            return;
        }

        // Otomatik sipariş numarası
        $orderNumber = 'SEV-' . date('Ymd') . '-' . str_pad(
            OutboundOrder::whereDate('created_at', today())->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        $order = OutboundOrder::create([
            'order_number' => $orderNumber,
            'status'       => 'pending',
        ]);

        foreach ($this->items as $item) {
            $order->items()->create([
                'product_id'      => $item['product_id'],
                'quantity'        => $item['quantity'],
                'picked_quantity' => 0,
            ]);
        }

        session()->flash('success', "Sipariş #{$orderNumber} başarıyla oluşturuldu.");

        return $this->redirect(route('outbound.index'), navigate: true);
    }

    public function render()
    {
        $products = Product::orderBy('name')->get()->map(function ($product) {
            $product->available_stock = $this->getAvailableStock($product->id);
            return $product;
        });

        return view('livewire.outbound.outbound-form', compact('products'));
    }
}
