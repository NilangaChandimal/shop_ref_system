<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product List') }}
            </h2>
            <div class="text-right">
                <span class="text-gray-600 font-bold">Total Products:</span>
                <span class="text-green-600 font-bold text-lg">{{ $totalProducts }}</span>
            </div>
        </div>
    </x-slot>

    <div class="mb-4">
        <form action="{{ route('products.index') }}" method="GET" class="flex items-center">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by product name"
                class="border border-gray-300 rounded px-4 py-2 mr-2"
            />
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Search
            </button>
        </form>
        
        <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create Product
        </a>
    </div>

    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">#</th>
                <th class="border border-gray-300 px-4 py-2">Name</th>
                <th class="border border-gray-300 px-4 py-2">Original Price</th>
                <th class="border border-gray-300 px-4 py-2">Displayed Price</th>
                <th class="border border-gray-300 px-4 py-2">Discount (%)</th>
                <th class="border border-gray-300 px-4 py-2">Selling Price</th>
                <th class="border border-gray-300 px-4 py-2">Profit</th>
                <th class="border border-gray-300 px-4 py-2">Unit</th>
                <th class="border border-gray-300 px-4 py-2">Quantity</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($product->original_price, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($product->displayed_price, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($product->discount, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($product->selling_price, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($product->profit, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->unit }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->quantity }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('products.edit', $product) }}" class="text-blue-500 hover:underline">Edit</a>

                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-500 hover:underline ml-2"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4">No products found with less than 10 quantity.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
