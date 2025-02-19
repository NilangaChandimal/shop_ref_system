<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
{
    $query = Customer::query();

    // Capture the search input
    $search = $request->input('search', ''); // Default to an empty string if not set

    if (!empty($search)) {
        $query->where('name', 'like', "%$search%");
    }

    // Apply the query and paginate results
    $customers = $query->paginate(100); // Paginate to handle large datasets
    $totalCustomers = $query->count(); // Get the total count of filtered results

    return view('customers.index', compact('customers', 'totalCustomers', 'search'));
}


    public function create()
{
    return view('customers.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15|unique:customers',
        'address' => 'required|string|max:500',
        'root' => 'required|string|max:255',
        // 'loan' => 'nullable|numeric|min:0',
    ]);

    Customer::create($validated);

    return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
}

public function edit(Customer $customer)
{
    return view('customers.edit', compact('customer'));
}

public function update(Request $request, Customer $customer)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15|unique:customers,phone_number,' . $customer->id,
        'address' => 'required|string|max:500',
        'root' => 'required|string|max:255',
        // 'loan' => 'nullable|numeric|min:0',
    ]);

    // Update customer details
    $customer->update($validated);

    return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
}

public function destroy(Customer $customer)
{
    $customer->delete();

    return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
}

}
