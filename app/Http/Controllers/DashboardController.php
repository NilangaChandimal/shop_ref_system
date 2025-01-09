<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
{
    // Calculate totals
    $totalCustomers = Customer::count();
    $totalProducts = Product::count();
    $totalSalesValue = Sale::sum('total_value');

    // Calculate total profit
    $totalProfit = Sale::with('products')->get()->reduce(function ($carry, $sale) {
        return $carry + $sale->products->sum(function ($product) {
            return ($product->selling_price - $product->original_price) * $product->pivot->quantity;
        });
    }, 0);

    // Get today's sales and profit
    $todaySales = Sale::whereDate('created_at', today())->get();
    $dailySalesValue = $todaySales->sum('total_value');
    $dailyProfit = $todaySales->reduce(function ($carry, $sale) {
        return $carry + $sale->products->sum(function ($product) {
            return ($product->selling_price - $product->original_price) * $product->pivot->quantity;
        });
    }, 0);

    // Get products with quantity less than 10
    $lowStockProducts = Product::where('quantity', '<', 10)->get(['name', 'quantity']);

    // Get top 10 most sold products
    $sales = Sale::with('products')->get();
    $productQuantities = [];

    foreach ($sales as $sale) {
        foreach ($sale->products as $product) {
            $productQuantities[$product->name] = ($productQuantities[$product->name] ?? 0) + $product->pivot->quantity;
        }
    }

    $topSoldProducts = collect($productQuantities)->sortDesc()->take(10);

    // Calculate monthly total sales and profit
    $monthlySalesData = [];
    $monthlyProfitData = [];
    $labels = [];

    // Loop through each month of the current year
    for ($month = 1; $month <= 12; $month++) {
        $monthlySales = Sale::whereMonth('created_at', $month)
                            ->whereYear('created_at', now()->year)
                            ->sum('total_value');
        $monthlySalesData[] = $monthlySales;

        $monthlyProfit = Sale::whereMonth('created_at', $month)
                             ->whereYear('created_at', now()->year)
                             ->get()
                             ->reduce(function ($carry, $sale) {
                                 return $carry + $sale->products->sum(function ($product) {
                                     return ($product->selling_price - $product->original_price) * $product->pivot->quantity;
                                 });
                             }, 0);
        $monthlyProfitData[] = $monthlyProfit;

        $labels[] = \Carbon\Carbon::createFromFormat('m', $month)->format('F'); // Get month name
    }

    // Get the monthly sales and profit for the current month
    $monthlySalesValue = $monthlySalesData[now()->month - 1];  // Current month's sales value
    $monthlyProfit = $monthlyProfitData[now()->month - 1];  // Current month's profit

    // Get customers with loans
    $customersWithLoans = Customer::with('loans')->get();

    // Pass data to the view
    return view('dashboard', compact(
        'totalCustomers',
        'totalProducts',
        'totalSalesValue',
        'totalProfit',
        'dailySalesValue',
        'dailyProfit',
        'lowStockProducts',
        'topSoldProducts',
        'monthlySalesValue',
        'monthlyProfit',
        'customersWithLoans',
        'monthlySalesData',
        'monthlyProfitData',
        'labels'
    ));
}


}
