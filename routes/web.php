<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Products\ProductForm;
use App\Livewire\Locations\LocationIndex;
use App\Livewire\Locations\LocationForm;
Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

});

// Master Veri Yönetimi Rotaları
Route::middleware(['auth', 'verified', 'role:admin,manager'])->group(function () {
    Route::livewire('/products', ProductIndex::class)->name('products.index');
    Route::livewire('/products/create', ProductForm::class)->name('products.create');
    Route::livewire('/products/{product}/edit', ProductForm::class)->name('products.edit');
    // Yetki gerektiren grup içerisine eklenecek kodlar:
    Route::get('/locations', LocationIndex::class)->name('locations.index');
    Route::get('/locations/create', LocationForm::class)->name('locations.create');
    Route::get('/locations/{location}/edit', LocationForm::class)->name('locations.edit');
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
