<?php

namespace App\Livewire\Inbound;

use App\Models\InboundOrder;
use App\Models\Location;
use App\Models\Stock;
use Livewire\Component;

class InboundPutaway extends Component
{
    public InboundOrder $order;

    // Her kalem için seçilen lokasyon (item_id => location_id)
    public array $locationSelections = [];

    // Başarılı yerleştirilen kalemler
    public array $putawayDone = [];

    public function mount(InboundOrder $order): void
    {
        $this->order = $order->load('items.product');

        foreach ($this->order->items as $item) {
            $this->locationSelections[$item->id] = '';
            $this->putawayDone[$item->id]        = false;
        }
    }

    public function putaway(int $itemId): void
    {
        $locationId = $this->locationSelections[$itemId] ?? null;

        $this->validate([
            "locationSelections.{$itemId}" => 'required|exists:locations,id',
        ], [
            "locationSelections.{$itemId}.required" => 'Lütfen bir lokasyon seçiniz.',
            "locationSelections.{$itemId}.exists"   => 'Geçersiz lokasyon.',
        ]);

        $item = $this->order->items()->with('product')->findOrFail($itemId);

        // Stok kaydını güncelle veya oluştur
        $stock = Stock::firstOrNew([
            'product_id'  => $item->product_id,
            'location_id' => $locationId,
        ]);

        $stock->quantity = ($stock->quantity ?? 0) + $item->received_quantity;
        $stock->save();

        $this->putawayDone[$itemId] = true;

        // Tüm kalemlerin yerleştirme işlemi tamamlanmışsa irsaliyeyi kapat
        if (!in_array(false, $this->putawayDone, true)) {
            $this->order->update(['status' => 'completed']);
            session()->flash('success', 'Tüm ürünler yerleştirildi. İrsaliye tamamlandı!');
            $this->redirect(route('inbound.index'), navigate: true);
        }
    }

    public function render()
    {
        $locations = Location::where('type', 'bin')->orderBy('code')->get();
        return view('livewire.inbound.inbound-putaway', compact('locations'));
    }
}
