<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------------------
        // ALAN (area) — Deponun ana bölgeleri
        // ----------------------------------------------------------------
        $alanA = Location::firstOrCreate(
            ['code' => 'A'],
            ['name' => 'A Blok — Elektronik', 'type' => 'area', 'parent_id' => null]
        );

        $alanB = Location::firstOrCreate(
            ['code' => 'B'],
            ['name' => 'B Blok — Giyim & Aksesuar', 'type' => 'area', 'parent_id' => null]
        );

        $alanC = Location::firstOrCreate(
            ['code' => 'C'],
            ['name' => 'C Blok — Ev & Yaşam', 'type' => 'area', 'parent_id' => null]
        );

        $alanD = Location::firstOrCreate(
            ['code' => 'D'],
            ['name' => 'D Blok — Kozmetik & Gıda', 'type' => 'area', 'parent_id' => null]
        );

        // ----------------------------------------------------------------
        // KORİDOR (aisle) — Her alana bağlı koridorlar
        // ----------------------------------------------------------------
        // A Bloku Koridorları
        $koA1 = Location::firstOrCreate(
            ['code' => 'A-K1'],
            ['name' => 'A Blok Koridor 1', 'type' => 'aisle', 'parent_id' => $alanA->id]
        );
        $koA2 = Location::firstOrCreate(
            ['code' => 'A-K2'],
            ['name' => 'A Blok Koridor 2', 'type' => 'aisle', 'parent_id' => $alanA->id]
        );

        // B Bloku Koridorları
        $koB1 = Location::firstOrCreate(
            ['code' => 'B-K1'],
            ['name' => 'B Blok Koridor 1', 'type' => 'aisle', 'parent_id' => $alanB->id]
        );

        // C Bloku Koridorları
        $koC1 = Location::firstOrCreate(
            ['code' => 'C-K1'],
            ['name' => 'C Blok Koridor 1', 'type' => 'aisle', 'parent_id' => $alanC->id]
        );

        // D Bloku Koridorları
        $koD1 = Location::firstOrCreate(
            ['code' => 'D-K1'],
            ['name' => 'D Blok Koridor 1', 'type' => 'aisle', 'parent_id' => $alanD->id]
        );

        // ----------------------------------------------------------------
        // RAF (rack) — Koridorlara bağlı raflar
        // ----------------------------------------------------------------
        $rafA1R1 = Location::firstOrCreate(
            ['code' => 'A-K1-R1'],
            ['name' => 'A/K1 Raf 1', 'type' => 'rack', 'parent_id' => $koA1->id]
        );
        $rafA1R2 = Location::firstOrCreate(
            ['code' => 'A-K1-R2'],
            ['name' => 'A/K1 Raf 2', 'type' => 'rack', 'parent_id' => $koA1->id]
        );
        $rafA2R1 = Location::firstOrCreate(
            ['code' => 'A-K2-R1'],
            ['name' => 'A/K2 Raf 1', 'type' => 'rack', 'parent_id' => $koA2->id]
        );
        $rafB1R1 = Location::firstOrCreate(
            ['code' => 'B-K1-R1'],
            ['name' => 'B/K1 Raf 1', 'type' => 'rack', 'parent_id' => $koB1->id]
        );
        $rafB1R2 = Location::firstOrCreate(
            ['code' => 'B-K1-R2'],
            ['name' => 'B/K1 Raf 2', 'type' => 'rack', 'parent_id' => $koB1->id]
        );
        $rafC1R1 = Location::firstOrCreate(
            ['code' => 'C-K1-R1'],
            ['name' => 'C/K1 Raf 1', 'type' => 'rack', 'parent_id' => $koC1->id]
        );
        $rafD1R1 = Location::firstOrCreate(
            ['code' => 'D-K1-R1'],
            ['name' => 'D/K1 Raf 1', 'type' => 'rack', 'parent_id' => $koD1->id]
        );

        // ----------------------------------------------------------------
        // GÖZ (bin) — Raflardaki bireysel gözler
        // ----------------------------------------------------------------
        $bins = [
            // A-K1-R1 gözleri
            ['code' => 'A-K1-R1-G1', 'name' => 'A/K1/R1 Göz 1', 'parent_id' => $rafA1R1->id],
            ['code' => 'A-K1-R1-G2', 'name' => 'A/K1/R1 Göz 2', 'parent_id' => $rafA1R1->id],
            ['code' => 'A-K1-R1-G3', 'name' => 'A/K1/R1 Göz 3', 'parent_id' => $rafA1R1->id],
            // A-K1-R2 gözleri
            ['code' => 'A-K1-R2-G1', 'name' => 'A/K1/R2 Göz 1', 'parent_id' => $rafA1R2->id],
            ['code' => 'A-K1-R2-G2', 'name' => 'A/K1/R2 Göz 2', 'parent_id' => $rafA1R2->id],
            // A-K2-R1 gözleri
            ['code' => 'A-K2-R1-G1', 'name' => 'A/K2/R1 Göz 1', 'parent_id' => $rafA2R1->id],
            ['code' => 'A-K2-R1-G2', 'name' => 'A/K2/R1 Göz 2', 'parent_id' => $rafA2R1->id],
            // B gözleri
            ['code' => 'B-K1-R1-G1', 'name' => 'B/K1/R1 Göz 1', 'parent_id' => $rafB1R1->id],
            ['code' => 'B-K1-R1-G2', 'name' => 'B/K1/R1 Göz 2', 'parent_id' => $rafB1R1->id],
            ['code' => 'B-K1-R2-G1', 'name' => 'B/K1/R2 Göz 1', 'parent_id' => $rafB1R2->id],
            // C gözleri
            ['code' => 'C-K1-R1-G1', 'name' => 'C/K1/R1 Göz 1', 'parent_id' => $rafC1R1->id],
            ['code' => 'C-K1-R1-G2', 'name' => 'C/K1/R1 Göz 2', 'parent_id' => $rafC1R1->id],
            // D gözleri
            ['code' => 'D-K1-R1-G1', 'name' => 'D/K1/R1 Göz 1', 'parent_id' => $rafD1R1->id],
            ['code' => 'D-K1-R1-G2', 'name' => 'D/K1/R1 Göz 2', 'parent_id' => $rafD1R1->id],
        ];

        foreach ($bins as $bin) {
            Location::firstOrCreate(
                ['code' => $bin['code']],
                ['name' => $bin['name'], 'type' => 'bin', 'parent_id' => $bin['parent_id']]
            );
        }
    }
}
