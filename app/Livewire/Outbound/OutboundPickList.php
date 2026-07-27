<?php

namespace App\Livewire\Outbound;

use App\Models\OutboundOrder;
use App\Models\Stock;
use Livewire\Component;

class OutboundPickList extends Component
{
    public OutboundOrder $order;

    // Her kalem için toplanan miktar girişi (item_id => picked_qty)
    public array $pickedQtys = [];

    public function mount(OutboundOrder $order): void
    {
        $this->order = $order->load(['items.product']);

        // Sipariş durumu picking'e geçir
        if ($this->order->status === 'pending') {
            $this->order->update(['status' => 'picking']);
        }

        foreach ($this->order->items as $item) {
            $this->pickedQtys[$item->id] = $item->picked_quantity;
        }
    }

    protected function rules(): array
    {
        $rules = [];
        foreach ($this->order->items as $item) {
            $rules["pickedQtys.{$item->id}"] = "required|integer|min:0|max:{$item->quantity}";
        }
        return $rules;
    }

    /**
     * Ürünün stokta bulunduğu lokasyonları döndürür.
     */
    public function getStockLocations(int $productId): \Illuminate\Support\Collection
    {
        return Stock::with('location')
            ->where('product_id', $productId)
            ->where('quantity', '>', 0)
            ->orderByDesc('quantity')
            ->get();
    }

    public function confirmPick(): void
    {
        $this->validate();

        foreach ($this->order->items as $item) {
            $item->update(['picked_quantity' => $this->pickedQtys[$item->id] ?? 0]);
        }

        // Tüm kalemler tam toplandı mı?
        $allPicked = $this->order->fresh()->items->every(fn ($i) => $i->isFullyPicked());

        if ($allPicked) {
            // Stokları düş
            foreach ($this->order->items as $item) {
                $remaining = $item->picked_quantity;

                // En yüksek stoğu olan lokasyondan başla
                $stocks = Stock::where('product_id', $item->product_id)
                    ->where('quantity', '>', 0)
                    ->orderByDesc('quantity')
                    ->get();

                foreach ($stocks as $stock) {
                    if ($remaining <= 0) break;
                    $deduct = min($stock->quantity, $remaining);
                    $stock->decrement('quantity', $deduct);
                    $remaining -= $deduct;
                }
            }

            $this->order->update(['status' => 'ready']);
            session()->flash('success', 'Tüm kalemler toplandı! Sipariş sevkiyata hazır.');
            $this->redirect(route('outbound.ship', $this->order->id), navigate: true);
        } else {
            session()->flash('info', 'Toplama miktarları kaydedildi.');
        }
    }

    public function render()
    {
        return view('livewire.outbound.outbound-picklist');
    }
}
