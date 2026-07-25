<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $products  = Product::all()->keyBy('sku');
        $locations = Location::where('type', 'bin')->get()->keyBy('code');

        // ------------------------------------------------------------------
        // Tamamlanan irsaliyelerin stoğu yerleştirilmiş gibi kayıt oluştur
        // ------------------------------------------------------------------

        // Elektronik — A Bloğu Gözleri
        $this->upsertStock($products['ELK-001'], $locations['A-K1-R1-G1'], 50);
        $this->upsertStock($products['ELK-002'], $locations['A-K1-R1-G2'], 30);
        $this->upsertStock($products['ELK-004'], $locations['A-K1-R1-G3'], 18);
        $this->upsertStock($products['ELK-005'], $locations['A-K1-R2-G1'], 40);
        $this->upsertStock($products['ELK-003'], $locations['A-K1-R2-G2'], 25);
        $this->upsertStock($products['ELK-006'], $locations['A-K2-R1-G1'], 15);

        // Giyim — B Bloğu Gözleri
        $this->upsertStock($products['GYM-001'], $locations['B-K1-R1-G1'], 100);
        $this->upsertStock($products['GYM-002'], $locations['B-K1-R1-G2'], 60);
        $this->upsertStock($products['GYM-003'], $locations['B-K1-R2-G1'], 35);
        $this->upsertStock($products['GYM-004'], $locations['A-K2-R1-G2'], 20);

        // Ev & Yaşam — C Bloğu Gözleri
        $this->upsertStock($products['EYS-003'], $locations['C-K1-R1-G1'], 8);
        $this->upsertStock($products['EYS-004'], $locations['C-K1-R1-G2'], 5);

        // Kozmetik & Gıda — D Bloğu Gözleri
        $this->upsertStock($products['KOZ-001'], $locations['D-K1-R1-G1'], 80);
        $this->upsertStock($products['KOZ-002'], $locations['D-K1-R1-G2'], 60);
    }

    private function upsertStock(Product $product, Location $location, int $quantity): void
    {
        Stock::updateOrCreate(
            ['product_id' => $product->id, 'location_id' => $location->id],
            ['quantity' => $quantity]
        );
    }
}
