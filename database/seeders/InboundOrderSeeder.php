<?php

namespace Database\Seeders;

use App\Models\InboundOrder;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InboundOrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all()->keyBy('sku');

        // ----------------------------------------------------------------
        // İrsaliye 1 — TAMAMLANDI (completed)
        // ----------------------------------------------------------------
        $order1 = InboundOrder::firstOrCreate(
            ['order_number' => 'IRV-20260720-0001'],
            ['status' => 'completed', 'created_at' => now()->subDays(5)]
        );
        $order1->items()->firstOrCreate(
            ['product_id' => $products['ELK-001']->id],
            ['expected_quantity' => 50, 'received_quantity' => 50]
        );
        $order1->items()->firstOrCreate(
            ['product_id' => $products['ELK-002']->id],
            ['expected_quantity' => 30, 'received_quantity' => 30]
        );
        $order1->items()->firstOrCreate(
            ['product_id' => $products['ELK-004']->id],
            ['expected_quantity' => 20, 'received_quantity' => 18]
        );

        // ----------------------------------------------------------------
        // İrsaliye 2 — TAMAMLANDI (completed), 3 gün önce
        // ----------------------------------------------------------------
        $order2 = InboundOrder::firstOrCreate(
            ['order_number' => 'IRV-20260722-0001'],
            ['status' => 'completed', 'created_at' => now()->subDays(3)]
        );
        $order2->items()->firstOrCreate(
            ['product_id' => $products['GYM-001']->id],
            ['expected_quantity' => 100, 'received_quantity' => 100]
        );
        $order2->items()->firstOrCreate(
            ['product_id' => $products['GYM-002']->id],
            ['expected_quantity' => 60, 'received_quantity' => 60]
        );
        $order2->items()->firstOrCreate(
            ['product_id' => $products['GYM-003']->id],
            ['expected_quantity' => 40, 'received_quantity' => 35]
        );

        // ----------------------------------------------------------------
        // İrsaliye 3 — İŞLEMDE (processing), put-away bekleniyor
        // ----------------------------------------------------------------
        $order3 = InboundOrder::firstOrCreate(
            ['order_number' => 'IRV-20260724-0001'],
            ['status' => 'processing', 'created_at' => now()->subDay()]
        );
        $order3->items()->firstOrCreate(
            ['product_id' => $products['EYS-001']->id],
            ['expected_quantity' => 15, 'received_quantity' => 15]
        );
        $order3->items()->firstOrCreate(
            ['product_id' => $products['EYS-002']->id],
            ['expected_quantity' => 10, 'received_quantity' => 8]
        );

        // ----------------------------------------------------------------
        // İrsaliye 4 — İŞLEMDE (processing), 2. irsaliye
        // ----------------------------------------------------------------
        $order4 = InboundOrder::firstOrCreate(
            ['order_number' => 'IRV-20260724-0002'],
            ['status' => 'processing', 'created_at' => now()->subDay()]
        );
        $order4->items()->firstOrCreate(
            ['product_id' => $products['KOZ-001']->id],
            ['expected_quantity' => 80, 'received_quantity' => 80]
        );
        $order4->items()->firstOrCreate(
            ['product_id' => $products['KOZ-002']->id],
            ['expected_quantity' => 60, 'received_quantity' => 60]
        );
        $order4->items()->firstOrCreate(
            ['product_id' => $products['KOZ-003']->id],
            ['expected_quantity' => 40, 'received_quantity' => 40]
        );

        // ----------------------------------------------------------------
        // İrsaliye 5 — BEKLİYOR (pending), bugün oluşturuldu
        // ----------------------------------------------------------------
        $order5 = InboundOrder::firstOrCreate(
            ['order_number' => 'IRV-20260725-0001'],
            ['status' => 'pending', 'created_at' => now()]
        );
        $order5->items()->firstOrCreate(
            ['product_id' => $products['ELK-006']->id],
            ['expected_quantity' => 25, 'received_quantity' => 0]
        );
        $order5->items()->firstOrCreate(
            ['product_id' => $products['ELK-007']->id],
            ['expected_quantity' => 12, 'received_quantity' => 0]
        );
        $order5->items()->firstOrCreate(
            ['product_id' => $products['ELK-008']->id],
            ['expected_quantity' => 8, 'received_quantity' => 0]
        );

        // ----------------------------------------------------------------
        // İrsaliye 6 — BEKLİYOR (pending), bugün 2. irsaliye
        // ----------------------------------------------------------------
        $order6 = InboundOrder::firstOrCreate(
            ['order_number' => 'IRV-20260725-0002'],
            ['status' => 'pending', 'created_at' => now()]
        );
        $order6->items()->firstOrCreate(
            ['product_id' => $products['GID-001']->id],
            ['expected_quantity' => 200, 'received_quantity' => 0]
        );
        $order6->items()->firstOrCreate(
            ['product_id' => $products['GID-002']->id],
            ['expected_quantity' => 150, 'received_quantity' => 0]
        );
    }
}
