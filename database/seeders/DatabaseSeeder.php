<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Sistem Yöneticisi kullanıcısı
        User::firstOrCreate(
            ['email' => 'bilal1@gmail.com'],
            [
                'name'     => 'Sistem Yöneticisi',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        // Depo yöneticisi (test için)
        User::firstOrCreate(
            ['email' => 'yonetici@wms.com'],
            [
                'name'     => 'Depo Yöneticisi',
                'password' => bcrypt('password'),
                'role'     => 'manager',
            ]
        );

        // Depo operatörü (test için)
        User::firstOrCreate(
            ['email' => 'operator@wms.com'],
            [
                'name'     => 'Depo Operatörü',
                'password' => bcrypt('password'),
                'role'     => 'operator',
            ]
        );

        // Mock veriler — sıralama önemli (bağımlılıklar nedeniyle)
        $this->call([
            ProductSeeder::class,       // Ürünler (bağımsız)
            LocationSeeder::class,      // Lokasyonlar (bağımsız)
            InboundOrderSeeder::class,  // İrsaliyeler (Product'a bağımlı)
            StockSeeder::class,         // Stok (Product + Location'a bağımlı)
        ]);
    }
}
