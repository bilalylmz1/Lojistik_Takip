<?php

namespace App\Livewire\Outbound;

use App\Models\OutboundOrder;
use Livewire\Component;
use Livewire\WithPagination;

class OutboundIndex extends Component
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
        $orders = OutboundOrder::withCount('items')
            ->when($this->search, fn ($q) =>
                $q->where('order_number', 'like', '%' . $this->search . '%')
            )
            ->when($this->statusFilter, fn ($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.outbound.outbound-index', compact('orders'));
    }
}
