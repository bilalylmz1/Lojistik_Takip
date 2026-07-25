<?php

namespace App\Livewire\Inbound;

use App\Models\InboundOrder;
use App\Models\Product;
use Livewire\Component;

class InboundForm extends Component
{
    public array $items = [];

    public function mount(): void
    {
        // Başlangıçta bir boş kalem ekle
        $this->addItem();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id'        => '',
            'expected_quantity' => 1,
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
            'items'                          => 'required|array|min:1',
            'items.*.product_id'             => 'required|exists:products,id',
            'items.*.expected_quantity'      => 'required|integer|min:1',
        ];
    }

    protected function messages(): array
    {
        return [
            'items.*.product_id.required'        => 'Her kalem için ürün seçilmelidir.',
            'items.*.product_id.exists'          => 'Seçilen ürün geçersiz.',
            'items.*.expected_quantity.required' => 'Her kalem için beklenen miktar girilmelidir.',
            'items.*.expected_quantity.min'      => 'Miktar en az 1 olmalıdır.',
        ];
    }

    public function save()
    {
        $this->validate();

        // Benzersiz irsaliye numarası üret
        $orderNumber = 'IRV-' . date('Ymd') . '-' . str_pad(
            InboundOrder::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        $order = InboundOrder::create([
            'order_number' => $orderNumber,
            'status'       => 'pending',
        ]);

        foreach ($this->items as $item) {
            $order->items()->create([
                'product_id'        => $item['product_id'],
                'expected_quantity' => $item['expected_quantity'],
                'received_quantity' => 0,
            ]);
        }

        session()->flash('success', "İrsaliye #{$orderNumber} başarıyla oluşturuldu.");

        return $this->redirect(route('inbound.index'), navigate: true);
    }

    public function render()
    {
        $products = Product::orderBy('name')->get();
        return view('livewire.inbound.inbound-form', compact('products'));
    }
}
