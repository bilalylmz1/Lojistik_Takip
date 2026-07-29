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
use App\Livewire\Stock\StockIndex;
use App\Livewire\Stock\StockAdjust;
use App\Livewire\Outbound\OutboundIndex;
use App\Livewire\Outbound\OutboundForm;
use App\Livewire\Outbound\OutboundPickList;
use App\Livewire\Outbound\OutboundShip;
use App\Models\InboundOrder;
use App\Models\OutboundOrder;
use App\Models\Product;
use App\Models\Stock;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $lowStockCount = Stock::whereBetween('quantity', [1, 5])->count();

        return view('dashboard', [
            'productCount' => Product::count(),
            'stockQuantity' => Stock::sum('quantity'),
            'lowStockCount' => $lowStockCount,
            'inboundToday' => InboundOrder::whereDate('created_at', today())->count(),
            'outboundToday' => OutboundOrder::whereDate('created_at', today())->count(),
            'pendingInboundCount' => InboundOrder::whereIn('status', ['pending', 'processing'])->count(),
            'pendingOutboundCount' => OutboundOrder::whereIn('status', ['pending', 'picking', 'ready'])->count(),
        ]);
    })->name('dashboard');
});


Route::middleware(['auth', 'verified', 'role:admin,manager'])->group(function () {

    Route::livewire('/products', ProductIndex::class)->name('products.index');
    Route::livewire('/products/create', ProductForm::class)->name('products.create');
    Route::livewire('/products/{product}/edit', ProductForm::class)->name('products.edit');


    Route::get('/locations', LocationIndex::class)->name('locations.index');
    Route::get('/locations/create', LocationForm::class)->name('locations.create');
    Route::get('/locations/{location}/edit', LocationForm::class)->name('locations.edit');


    Route::get('/inbound', InboundIndex::class)->name('inbound.index');
    Route::get('/inbound/create', InboundForm::class)->name('inbound.create');
    Route::get('/inbound/{order}/receive', InboundReceive::class)->name('inbound.receive');
    Route::get('/inbound/{order}/putaway', InboundPutaway::class)->name('inbound.putaway');


    Route::get('/stock', StockIndex::class)->name('stock.index');
    Route::get('/stock/{stock}/adjust', StockAdjust::class)->name('stock.adjust');


    Route::get('/outbound', OutboundIndex::class)->name('outbound.index');
    Route::get('/outbound/create', OutboundForm::class)->name('outbound.create');
    Route::get('/outbound/{order}/picklist', OutboundPickList::class)->name('outbound.picklist');
    Route::get('/outbound/{order}/ship', OutboundShip::class)->name('outbound.ship');
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
