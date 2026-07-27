<?php

namespace App\Livewire\Stock;

use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;

class StockIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $view = 'location'; // 'location' veya 'product'

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingView(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Stock::with(['product', 'location', 'location.parent'])
            ->where('quantity', '>', 0)
            ->when($this->search, function ($q) {
                $q->whereHas('product', fn ($p) =>
                    $p->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                )->orWhereHas('location', fn ($l) =>
                    $l->where('code', 'like', '%' . $this->search . '%')
                );
            });

        if ($this->view === 'product') {
            // Ürün bazlı: aynı ürünün tüm lokasyonlardaki toplamı
            $stocks = $query->orderBy('product_id')->paginate(15);
        } else {
            // Lokasyon bazlı: lokasyon kodu sıralı
            $stocks = $query
                ->join('locations', 'stocks.location_id', '=', 'locations.id')
                ->orderBy('locations.code')
                ->select('stocks.*')
                ->paginate(15);
        }

        // Ürün bazlı özet (toplam stok per ürün)
        $productTotals = Stock::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_qty')
            ->groupBy('product_id')
            ->when($this->search, fn ($q) =>
                $q->whereHas('product', fn ($p) =>
                    $p->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                )
            )
            ->paginate(15);

        return view('livewire.stock.stock-index', compact('stocks', 'productTotals'));
    }
}
