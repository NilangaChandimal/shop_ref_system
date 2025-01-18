<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/discounts', [ProductController::class, 'discounts'])->name('products.discounts');
    Route::put('/products/{product}/discount', [ProductController::class, 'updateDiscount'])->name('products.updateDiscount');
    Route::get('/products/display', [ProductController::class, 'display'])->name('products.display');

});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}/return', [SaleController::class, 'showReturnPage'])->name('returns.create');
    Route::post('/sales/{sale}/return', [SaleController::class, 'processReturn'])->name('returns.store');

});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/customer/{customerId}/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::put('/loan/{id}', [LoanController::class, 'update'])->name('loan.update');
    Route::delete('/loans/{id}', [LoanController::class, 'destroy'])->name('loans.destroy');


});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
});
