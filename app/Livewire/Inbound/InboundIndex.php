<?php

namespace App\Livewire\Inbound;

use App\Models\InboundOrder;
use Livewire\Component;
use Livewire\WithPagination;

class InboundIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = InboundOrder::withCount('items')
            ->when($this->search, fn ($q) =>
                $q->where('order_number', 'like', '%' . $this->search . '%')
            )
            ->when($this->statusFilter, fn ($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.inbound.inbound-index', compact('orders'));
    }
}
