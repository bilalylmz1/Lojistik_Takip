<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class ProductForm extends Component
{
    public ?Product $product = null;
    public $sku = '';
    public $barcode = '';
    public $name = '';

    // Sayfa yüklendiğinde düzenleme muodundaysa ürün bilgilerini forma doldurur
    public function mount(Product $product = null)
    {
        if ($product && $product->exists) {
            $this->product = $product;
            $this->sku = $product->sku;
            $this->barcode = $product->barcode;
            $this->name = $product->name;
        }
    }

    // Veritabanı doğrulama kuralları
    protected function rules()
    {
        return [
            // SKU benzersiz olmalı, ancak düzenleme modundaysa kendi ID'sini hariç tutmalı
            //Hiçbir şey değiştirmeden kaydet dediğinde kaydedebilmeni de sağlarr mesela Bilal kaydın var Unique olmadığı için değiştirmediğin sürece bunu kaydedebilmeni sağlar.
            'sku' => 'required|string|unique:products,sku,' . optional($this->product)->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . optional($this->product)->id,
            'name' => 'required|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        Product::updateOrCreate(
            //ID eğer varsa Update yapar yoksa Insert yapar. 
            // 
            ['id' => optional($this->product)->id],
            [
                'sku' => $this->sku,
                'barcode' => $this->barcode,
                'name' => $this->name,
            ]
        );

        // Kayıt sonrası ürünler listesine SPA mantığıyla (navigate) yönlendirir
        return $this->redirect(route('products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.products.product-form');
    }
}