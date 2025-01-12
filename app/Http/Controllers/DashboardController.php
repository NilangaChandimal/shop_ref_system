<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Sale;
use Illuminate\Container\Attributes\DB as AttributesDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $monthlySalesValue = $monthlySalesData[now()->month - 1];
        $monthlyProfit = $monthlyProfitData[now()->month - 1];

        // Get customers with loans (exclude customers with no loans)
        $customersWithLoans = Customer::whereHas('loans', function ($query) {
            $query->where('amount', '>', 0);
        })->with('loans')->get();

        // Get customers with sales (exclude customers with no sales)
        $customerSales = Customer::whereHas('sales', function ($query) {
            $query->selectRaw('customer_id, SUM(total_value) as total_sales_value')
                  ->groupBy('customer_id')
                  ->havingRaw('SUM(total_value) > 0');
        })->with(['sales' => function ($query) {
            $query->selectRaw('customer_id, SUM(total_value) as total_sales_value')
                  ->groupBy('customer_id');
        }])->get();

        // Map customer sales to an array
        $customerSalesData = $customerSales->map(function ($customer) {
            return [
                'customer_name' => $customer->name,
                'total_sales_value' => $customer->sales->sum('total_sales_value'),
            ];
        });

        // Get refund data
        $refundData = ProductReturn::select('customer_name', DB::raw('SUM(returned_quantity * price_per_unit) as total_refund'))
                                          ->groupBy('customer_name')
                                          ->get();


        $totalReturns = ProductReturn::sum(DB::raw('returned_quantity * price_per_unit'));

        // Calculate total loan amount
        $totalLoans = DB::table('loans')->sum('amount');

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
            'labels',
            'customerSalesData',
            'refundData',
            'totalReturns',
            'totalLoans'
        ));
    }
}
