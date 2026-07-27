<?php

namespace App\Livewire\Outbound;

use App\Models\OutboundOrder;
use Livewire\Component;

class OutboundShip extends Component
{
    public OutboundOrder $order;

    public function mount(OutboundOrder $order): void
    {
        $this->order = $order->load(['items.product']);
    }

    public function confirmShipment(): void
    {
        if ($this->order->status !== 'ready') {
            session()->flash('error', 'Sadece "Hazır" durumundaki siparişler sevk edilebilir.');
            return;
        }

        $this->order->update(['status' => 'shipped']);

        session()->flash('success', "Sipariş #{$this->order->order_number} başarıyla sevk edildi!");

        $this->redirect(route('outbound.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.outbound.outbound-ship');
    }
}
