<?php

namespace App\Livewire\Stock;

use App\Models\Stock;
use Livewire\Component;

class StockAdjust extends Component
{
    public Stock $stock;

    public string $adjustType  = 'add';   // 'add' veya 'subtract'
    public int    $amount      = 1;
    public string $reason      = '';

    public function mount(Stock $stock): void
    {
        $this->stock = $stock->load(['product', 'location']);
    }

    protected function rules(): array
    {
        return [
            'adjustType' => 'required|in:add,subtract',
            'amount'     => 'required|integer|min:1',
            'reason'     => 'required|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'amount.min'    => 'Miktar en az 1 olmalıdır.',
            'reason.required' => 'Düzeltme sebebi zorunludur.',
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->adjustType === 'subtract') {
            if ($this->amount > $this->stock->quantity) {
                $this->addError('amount', "Mevcut stok ({$this->stock->quantity}) düşülmek istenen miktardan az.");
                return;
            }
            $this->stock->decrement('quantity', $this->amount);
        } else {
            $this->stock->increment('quantity', $this->amount);
        }

        session()->flash('success', "Stok başarıyla güncellendi. Yeni miktar: {$this->stock->fresh()->quantity}");

        $this->redirect(route('stock.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.stock.stock-adjust');
    }
}
