<?php

use App\Http\Controllers\SaleController;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/sales/{id}', function ($id) {
    $sale = Sale::with(['customer', 'products'])->findOrFail($id);
    $allProducts = \App\Models\Product::all();

    return response()->json([
        'id' => $sale->id,
        'customer' => $sale->customer,
        'products' => $sale->products,
        'total_value' => $sale->total_value,
        'paid_value' => $sale->paid_value,
        'balance' => $sale->balance,
        'allProducts' => $allProducts
    ]);
});

// Route::get('/sales/{customerId}/products', [SaleController::class, 'getCustomerProducts']);
