<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="original_price" class="block text-gray-700 font-bold mb-2">Original Price:</label>
                        <input type="number" id="original_price" name="original_price" step="0.01"
                            value="{{ old('original_price', $product->original_price) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="displayed_price" class="block text-gray-700 font-bold mb-2">Displayed Price:</label>
                        <input type="number" id="displayed_price" name="displayed_price" step="0.01"
                            value="{{ old('displayed_price', $product->displayed_price) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="discount" class="block text-gray-700 font-bold mb-2">Discount (%):</label>
                        <input type="number" id="discount" name="discount" step="0.01"
                            value="{{ old('discount', $product->discount) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="unit" class="block text-gray-700 font-bold mb-2">Unit:</label>
                        <input type="text" id="unit" name="unit" value="{{ old('unit', $product->unit) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700 font-bold mb-2">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
