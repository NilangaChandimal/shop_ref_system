<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Loan;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
{
    $sales = Sale::all();
    $totalSalesValue = $sales->sum('total_value');

    return view('sales.index', compact('sales', 'totalSalesValue'));
}

    public function create()
{
    $customers = Customer::all();
    $products = Product::all();
    return view('sales.create', compact('customers', 'products'));
}
public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'paid_value' => 'required|numeric|min:0',
    ]);

    $totalValue = 0;
    foreach ($validated['products'] as $product) {
        $productModel = Product::findOrFail($product['product_id']);

        if ($productModel->quantity < $product['quantity']) {
            return redirect()->back()->withErrors([
                'products' => "Insufficient stock for product: {$productModel->name}. Available quantity: {$productModel->quantity}.",
            ])->withInput();
        }

        $totalValue += $productModel->selling_price * $product['quantity'];
    }

    $paidValue = $validated['paid_value'];
    $balance = $paidValue - $totalValue;

    $sale = Sale::create([
        'customer_id' => $validated['customer_id'],
        'total_value' => $totalValue,
        'paid_value' => $paidValue,
        'balance' => $balance,
    ]);

    foreach ($validated['products'] as $product) {
        $productModel = Product::findOrFail($product['product_id']);
        $productModel->decrement('quantity', $product['quantity']);
        $sale->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
    }

    if ($balance < 0) {
        Loan::create([
            'customer_id' => $validated['customer_id'],
            'amount' => abs($balance),
        ]);
    }

    return redirect()->route('sales.create')->with('success', 'Sale has been successfully created. Loan recorded for negative balance.');
}

public function showReturnPage($saleId)
{
    $sale = Sale::findOrFail($saleId);
    $products = $sale->products;

    return view('sales.return', compact('sale', 'products'));
}

public function processReturn(Request $request, $saleId)
{
    $sale = Sale::findOrFail($saleId);
    $products = $request->input('products');
    $customer = $sale->customer;
    $totalRefund = 0;
    $action = $request->input('action');

    $profitAdjustment = 0;

    foreach ($products as $productId => $quantity) {
        if ($quantity > 0) {
            $product = Product::findOrFail($productId);
            $saleProduct = $sale->products()->where('product_id', $productId)->first();

            if ($saleProduct && $saleProduct->pivot->quantity >= $quantity) {
                $refundAmount = $product->selling_price * $quantity;

                $profitAdjustment += $quantity * ($product->selling_price - $product->original_price);

                if ($action === 'adjust_profit_only') {
                    ProductReturn::create([
                        'sale_id' => $sale->id,
                        'customer_name' => $customer->name,
                        'product_name' => $product->name,
                        'returned_quantity' => $quantity,
                        'price_per_unit' => $product->selling_price,
                    ]);
                }

                if ($action === 'adjust_quantity_and_profit') {
                    $product->increment('quantity', $quantity);
                    $saleProduct->pivot->decrement('quantity', $quantity);
                } elseif ($action === 'adjust_profit_only') {
                    $saleProduct->pivot->decrement('quantity', $quantity);
                }

                $sale->total_value -= $refundAmount;
                $sale->balance += $refundAmount;
                $sale->save();
            }
        }
    }

    // $sale->profit -= $profitAdjustment;
    // $sale->save();

    if ($totalRefund > 0) {
        if ($customer->loan > 0) {
            if ($customer->loan >= $totalRefund) {
                $customer->loan -= $totalRefund;
                $totalRefund = 0;
            } else {
                $totalRefund -= $customer->loan;
                $customer->loan = 0;
            }
            $customer->save();
        }

        if ($totalRefund > 0) {
            $sale->balance += $totalRefund;
            $sale->save();
        }
    }

    return redirect()->route('sales.index')->with('success', 'Product returned and totals updated successfully.');
}
public function destroy($id)
{
    $sale = Sale::with('products')->findOrFail($id);

    foreach ($sale->products as $product) {
        $product->quantity += $product->pivot->quantity;
        $product->save();
    }

    $sale->products()->detach();
    $sale->delete();

    return redirect()->back()->with('success', 'Sale deleted and stock updated successfully.');
}


}
