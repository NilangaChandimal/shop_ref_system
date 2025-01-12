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
    // Validate request data
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'paid_value' => 'required|numeric|min:0',
    ]);

    // Calculate total value
    $totalValue = 0;
    foreach ($validated['products'] as $product) {
        $productModel = Product::findOrFail($product['product_id']);

        // Check stock
        if ($productModel->quantity < $product['quantity']) {
            return redirect()->back()->withErrors([
                'products' => "Insufficient stock for product: {$productModel->name}. Available quantity: {$productModel->quantity}.",
            ])->withInput();
        }

        $totalValue += $productModel->selling_price * $product['quantity'];
    }

    // Calculate balance
    $paidValue = $validated['paid_value'];
    $balance = $paidValue - $totalValue;

    // Create the sale
    $sale = Sale::create([
        'customer_id' => $validated['customer_id'],
        'total_value' => $totalValue,
        'paid_value' => $paidValue,
        'balance' => $balance,
    ]);

    // Attach products to the sale and update stock
    foreach ($validated['products'] as $product) {
        $productModel = Product::findOrFail($product['product_id']);
        $productModel->decrement('quantity', $product['quantity']);
        $sale->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
    }

    // Create a loan if balance is negative
    if ($balance < 0) {
        Loan::create([
            'customer_id' => $validated['customer_id'],
            'amount' => abs($balance), // Loan amount is the absolute value of the negative balance
        ]);
    }

    return redirect()->route('sales.create')->with('success', 'Sale has been successfully created. Loan recorded for negative balance.');
}

public function showReturnPage($saleId)
{
    $sale = Sale::findOrFail($saleId);
    $products = $sale->products; // Get all products associated with this sale

    return view('sales.return', compact('sale', 'products'));
}

public function processReturn(Request $request, $saleId)
{
    $sale = Sale::findOrFail($saleId);
    $products = $request->input('products'); // Array of product IDs and quantities
    $customer = $sale->customer;
    $totalRefund = 0; // Total refund amount for returned products
    $action = $request->input('action'); // Action from the form (button clicked)

    $profitAdjustment = 0; // Variable to adjust profit

    foreach ($products as $productId => $quantity) {
        if ($quantity > 0) {
            $product = Product::findOrFail($productId);
            $saleProduct = $sale->products()->where('product_id', $productId)->first();

            if ($saleProduct && $saleProduct->pivot->quantity >= $quantity) {
                // Refund amount for returned product
                $refundAmount = $product->selling_price * $quantity;

                // Calculate profit adjustment
                $profitAdjustment += $quantity * ($product->selling_price - $product->original_price);

                // Save return data if "adjust_profit_only" is selected
                if ($action === 'adjust_profit_only') {
                    ProductReturn::create([
                        'sale_id' => $sale->id,
                        'customer_name' => $customer->name,
                        'product_name' => $product->name,
                        'returned_quantity' => $quantity,
                        'price_per_unit' => $product->selling_price,
                    ]);
                }

                // Update product and sale details based on action
                if ($action === 'adjust_quantity_and_profit') {
                    $product->increment('quantity', $quantity); // Return stock
                    $saleProduct->pivot->decrement('quantity', $quantity); // Reduce sale quantity
                } elseif ($action === 'adjust_profit_only') {
                    $saleProduct->pivot->decrement('quantity', $quantity); // Reduce sale quantity only
                }

                // Deduct refund amount from total value of the sale
                $sale->total_value -= $refundAmount;
                $sale->save();
            }
        }
    }

    // Update profit
    $sale->profit -= $profitAdjustment;
    $sale->save();

    // Handle loan and balance updates
    if ($totalRefund > 0) {
        if ($customer->loan > 0) {
            if ($customer->loan >= $totalRefund) {
                $customer->loan -= $totalRefund;
                $totalRefund = 0; // Fully covered by the loan
            } else {
                $totalRefund -= $customer->loan; // Partially covered
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

}
