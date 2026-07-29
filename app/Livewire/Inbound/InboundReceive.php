<?php

namespace App\Livewire\Inbound;

use App\Models\InboundOrder;
use Livewire\Component;

class InboundReceive extends Component
{
    public InboundOrder $order;
    public array $receivedQtys = [];

    public function mount(InboundOrder $order): void
    {
        $this->order = $order->load('items.product');

        foreach ($this->order->items as $item) {
            $this->receivedQtys[$item->id] = $item->received_quantity;
        }
    }

    protected function rules(): array
    {
        $rules = [];
        foreach ($this->order->items as $item) {
            $rules["receivedQtys.{$item->id}"] = 'required|integer|min:0';
        }
        return $rules;
    }

    public function save(): void
    {
        $this->validate();

        foreach ($this->order->items as $item) {
            $item->update([
                'received_quantity' => $this->receivedQtys[$item->id] ?? 0,
            ]);
        }


        $this->order->update(['status' => 'processing']);

        session()->flash('success', 'Teslim alınan miktarlar kaydedildi.');

        $this->redirect(route('inbound.putaway', $this->order->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.inbound.inbound-receive');
    }
}
