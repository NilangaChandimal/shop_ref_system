<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $query = Product::query();

    $search = $request->input('search', '');

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
        'shop_price' => 'required|numeric|min:0',
        'discount' => 'required|numeric|min:0|max:100',
        'unit' => 'required|string|max:50',
        'quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    $selling_price = $validated['shop_price'];
    $profit = $selling_price - $validated['original_price'];

    Product::create([
        'name' => $validated['name'],
        'original_price' => $validated['original_price'],
        'displayed_price' => $validated['displayed_price'],
        'shop_price' => $validated['shop_price'],
        'discount' => $validated['discount'],
        'selling_price' => $selling_price,
        'profit' => $profit,
        'unit' => $validated['unit'],
        'quantity' => $validated['quantity'],
        'image' => $imagePath,
    ]);

    return redirect()->route('products.index')->with('success', 'Product created successfully!');
}
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }


    public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'original_price' => 'required|numeric|min:0',
        'displayed_price' => 'required|numeric|min:0',
        'shop_price' => 'required|numeric|min:0',
        'discount' => 'required|numeric|min:0|max:100',
        'unit' => 'required|string|max:50',
        'quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Recalculate selling price & profit
    $selling_price = $request->shop_price;
    $profit = $selling_price - $request->original_price;

    $product->name = $request->name;
    $product->original_price = $request->original_price;
    $product->displayed_price = $request->displayed_price;
    $product->shop_price = $request->shop_price;
    $product->discount = $request->discount;
    $product->selling_price = $selling_price;
    $product->profit = $profit;
    $product->unit = $request->unit;
    $product->quantity = $request->quantity;

    // Handle image upload
    if ($request->hasFile('image')) {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $imagePath = $request->file('image')->store('products', 'public');
        $product->image = $imagePath;
    }

    $product->save();

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

    $search = $request->input('search', '');

    if (!empty($search)) {
        $query->where('name', 'like', "%$search%");
    }

    $products = $query->paginate(100);

    return view('products.discounts', compact('products', 'search'));
}

public function updateDiscount(Request $request, Product $product)
{
    $validated = $request->validate([
        'discount' => 'required|numeric|min:0|max:100',
    ]);

    $product->discount = $validated['discount'];
    $product->selling_price = $product->displayed_price * ((100 - $product->discount) / 100);
    $product->profit = $product->original_price - $product->selling_price;
    $product->save();

    return redirect()->route('products.discounts')->with('success', 'Discount updated successfully!');
}

public function display(Request $request)
{
    $search = $request->input('search');

    $products = Product::when($search, function($query, $search) {
        return $query->where('name', 'like', '%' . $search . '%');
    })->get();

    return view('products.display', compact('products'));
}

public function add(Request $request)
{
    $query = Product::query();

    $search = $request->input('search', '');

    if (!empty($search)) {
        $query->where('name', 'like', "%$search%");
    }

    $products = $query->paginate(100);

    return view('products.addProduct', compact('products', 'search'));
}

public function updateProduct(Request $request, Product $product)
{
    $validated = $request->validate([
        'quantity' => 'required|integer|min:0',
    ]);

    // Add the quantity from the form to the existing quantity
    $product->quantity += $validated['quantity'];
    $product->save();

    return redirect()->route('products.add')->with('success', 'Product stock updated successfully!');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls',
    ]);

    $file = $request->file('file');
    $spreadsheet = IOFactory::load($file->getRealPath());
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // Skip header row
    foreach (array_slice($rows, 1) as $row) {
        $id = intval($row[0] ?? 0); // ✅ '#' column (product ID)
        $name = trim($row[2] ?? '');
        $original_price = floatval(str_replace(['Rs.', ' '], '', $row[3] ?? 0));
        $displayed_price = floatval(str_replace(['Rs.', ' '], '', $row[4] ?? 0));
        $shop_price = floatval(str_replace(['Rs.', ' '], '', $row[5] ?? 0));
        $discount = floatval(str_replace(['%', ' '], '', $row[6] ?? 0));
        $profit = floatval(str_replace(['Rs.', ' '], '', $row[7] ?? 0));
        $unit = trim($row[8] ?? '');
        $quantity = intval($row[9] ?? 0);

        if (!$name || !$original_price || !$displayed_price) {
            continue;
        }

        if ($id && Product::find($id)) {
            $product = Product::find($id);
            $product->update([
                'name' => $name,
                'original_price' => $original_price,
                'displayed_price' => $displayed_price,
                'shop_price' => $shop_price,
                'discount' => round($discount, 2),
                'selling_price' => round($shop_price, 2),
                'profit' => round($profit, 2),
                'unit' => $unit,
                'quantity' => $quantity,
            ]);

        } else {
            Product::create([
                'name' => $name,
                'original_price' => $original_price,
                'displayed_price' => $displayed_price,
                'shop_price' => $shop_price,
                'discount' => round($discount, 2),
                'selling_price' => round($shop_price, 2),
                'profit' => round($profit, 2),
                'unit' => $unit,
                'quantity' => $quantity,
                'image' => null,
            ]);
        }
    }

    return back()->with('success', 'Products imported/updated successfully!');
}


}
