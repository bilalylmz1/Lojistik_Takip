<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Elektronik
            ['sku' => 'ELK-001', 'barcode' => '8690000000001', 'name' => 'Samsung Galaxy A54 Akıllı Telefon'],
            ['sku' => 'ELK-002', 'barcode' => '8690000000002', 'name' => 'Apple iPhone 15 Akıllı Telefon'],
            ['sku' => 'ELK-003', 'barcode' => '8690000000003', 'name' => 'Xiaomi Redmi Note 12 Akıllı Telefon'],
            ['sku' => 'ELK-004', 'barcode' => '8690000000004', 'name' => 'Sony WH-1000XM5 Kulaklık'],
            ['sku' => 'ELK-005', 'barcode' => '8690000000005', 'name' => 'JBL Flip 6 Bluetooth Hoparlör'],
            ['sku' => 'ELK-006', 'barcode' => '8690000000006', 'name' => 'Logitech MX Master 3 Mouse'],
            ['sku' => 'ELK-007', 'barcode' => '8690000000007', 'name' => 'Apple iPad 10. Nesil'],
            ['sku' => 'ELK-008', 'barcode' => '8690000000008', 'name' => 'Asus VivoBook 15 Laptop'],

            // Giyim
            ['sku' => 'GYM-001', 'barcode' => '8690000000101', 'name' => 'Nike Air Max 270 Spor Ayakkabı'],
            ['sku' => 'GYM-002', 'barcode' => '8690000000102', 'name' => 'Adidas Ultraboost 22 Koşu Ayakkabısı'],
            ['sku' => 'GYM-003', 'barcode' => '8690000000103', 'name' => 'Levi\'s 501 Original Fit Jean'],
            ['sku' => 'GYM-004', 'barcode' => '8690000000104', 'name' => 'Columbia Omni-Heat Mont'],

            // Ev & Yaşam
            ['sku' => 'EYS-001', 'barcode' => '8690000000201', 'name' => 'Tefal Pro Inox Tencere Seti'],
            ['sku' => 'EYS-002', 'barcode' => '8690000000202', 'name' => 'Philips 3000 Serisi Hava Fritözü'],
            ['sku' => 'EYS-003', 'barcode' => '8690000000203', 'name' => 'Dyson V15 Detect Süpürge'],
            ['sku' => 'EYS-004', 'barcode' => '8690000000204', 'name' => 'IKEA KALLAX Kitaplık'],

            // Kozmetik
            ['sku' => 'KOZ-001', 'barcode' => '8690000000301', 'name' => 'L\'Oréal Revitalift Serum'],
            ['sku' => 'KOZ-002', 'barcode' => '8690000000302', 'name' => 'Maybelline Fit Me Fondöten'],
            ['sku' => 'KOZ-003', 'barcode' => '8690000000303', 'name' => 'Neutrogena Hydro Boost Nemlendirici'],

            // Gıda (ambalajlı)
            ['sku' => 'GID-001', 'barcode' => '8690000000401', 'name' => 'Nescafé Gold Kahve 200g'],
            ['sku' => 'GID-002', 'barcode' => '8690000000402', 'name' => 'Ülker Çikolatalı Gofret 36\'lı'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['sku' => $product['sku']], $product);
        }
    }
}
