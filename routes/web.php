<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Products\ProductForm;
use App\Livewire\Locations\LocationIndex;
use App\Livewire\Locations\LocationForm;
use App\Livewire\Inbound\InboundIndex;
use App\Livewire\Inbound\InboundForm;
use App\Livewire\Inbound\InboundReceive;
use App\Livewire\Inbound\InboundPutaway;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// Master Veri Yönetimi ve Operasyon Rotaları
Route::middleware(['auth', 'verified', 'role:admin,manager'])->group(function () {
    // Ürünler
    Route::livewire('/products', ProductIndex::class)->name('products.index');
    Route::livewire('/products/create', ProductForm::class)->name('products.create');
    Route::livewire('/products/{product}/edit', ProductForm::class)->name('products.edit');

    // Lokasyonlar
    Route::get('/locations', LocationIndex::class)->name('locations.index');
    Route::get('/locations/create', LocationForm::class)->name('locations.create');
    Route::get('/locations/{location}/edit', LocationForm::class)->name('locations.edit');

    // Mal Kabul (Inbound)
    Route::get('/inbound', InboundIndex::class)->name('inbound.index');
    Route::get('/inbound/create', InboundForm::class)->name('inbound.create');
    Route::get('/inbound/{order}/receive', InboundReceive::class)->name('inbound.receive');
    Route::get('/inbound/{order}/putaway', InboundPutaway::class)->name('inbound.putaway');
});
if (app()->environment('local')) {
    Route::get('/dev-login', function () {
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'admin@wms.com'],
            [
                'name' => 'Sistem Yöneticisi',
                'password' => bcrypt('password'),
                'role' => 'admin' // Sistem yöneticisi yetki seviyesi
            ]
        );

        auth()->login($user);

        return redirect('/products');
    });
}

require __DIR__.'/settings.php';
