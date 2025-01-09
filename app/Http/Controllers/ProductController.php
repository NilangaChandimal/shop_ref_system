<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $query = Product::query();

    // Capture the search input
    $search = $request->input('search', ''); // Default to an empty string if not set

    if (!empty($search)) {
        $query->where('name', 'like', "%$search%");
    }

    $products = $query->paginate(100);
    $totalProducts = $query->count();

    return view('products.index', compact('products', 'totalProducts', 'search'));
}

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'original_price' => 'required|numeric|min:0',
            'displayed_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
        ]);

        // Calculate selling price and profit
        $selling_price = $validated['displayed_price'] * ((100 - $validated['discount']) / 100);
        $profit = $selling_price - $validated['original_price'];

        // Save the product
        Product::create([
            'name' => $validated['name'],
            'original_price' => $validated['original_price'],
            'displayed_price' => $validated['displayed_price'],
            'discount' => $validated['discount'],
            'selling_price' => $selling_price,
            'profit' => $profit,
            'unit' => $validated['unit'],
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }


    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'original_price' => 'required|numeric|min:0',
            'displayed_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
        ]);

        // Calculate selling price and profit
        $selling_price = $validated['displayed_price'] * ((100 - $validated['discount']) / 100);
        $profit = $selling_price - $validated['original_price'];

        // Update the product
        $product->update([
            'name' => $validated['name'],
            'original_price' => $validated['original_price'],
            'displayed_price' => $validated['displayed_price'],
            'discount' => $validated['discount'],
            'selling_price' => $selling_price,
            'profit' => $profit,
            'unit' => $validated['unit'],
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function discounts(Request $request)
{
    $query = Product::query();

    // Capture the search input
    $search = $request->input('search', ''); // Default to an empty string if not set

    if (!empty($search)) {
        $query->where('name', 'like', "%$search%");
    }

    // Fetch filtered results
    $products = $query->paginate(10); // Paginate results for better handling of large data

    return view('products.discounts', compact('products', 'search'));
}

public function updateDiscount(Request $request, Product $product)
{
    $validated = $request->validate([
        'discount' => 'required|numeric|min:0|max:100',
    ]);

    // Update the discount and recalculate selling price
    $product->discount = $validated['discount'];
    $product->selling_price = $product->displayed_price * ((100 - $product->discount) / 100);
    $product->profit = $product->original_price - $product->selling_price;
    $product->save();

    return redirect()->route('products.discounts')->with('success', 'Discount updated successfully!');
}

}
