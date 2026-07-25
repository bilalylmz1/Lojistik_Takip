<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteProduct($id)
    {   //Product nesnesi döndürür eğer bulunmazsa 404 hatası fırlatılacak.     
        $product = Product::findOrFail($id);
        $product->delete();
    }

    public function render()
    {
        //SQL ifadelerini LİKE %aranan_kelime% şeklinde bulabilmek için yaptın. 
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orWhere('barcode', 'like', '%' . $this->search . '%')
            ->paginate(10);
            //Burada active 
       /*     Product::where('active', true)
    ->where(function ($query) {
        $query->where('name', 'like', ...)
              ->orWhere('sku', 'like', ...);
    });*/
        return view('livewire.products.product-index', [
            'products' => $products,
        ]);
    }
}