<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <!-- Original Price -->
                    <div class="mb-4">
                        <label for="original_price" class="block text-gray-700 font-bold mb-2">Original Price:</label>
                        <input type="number" id="original_price" name="original_price" step="0.01"
                            value="{{ old('original_price', $product->original_price) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <!-- Displayed Price -->
                    <div class="mb-4">
                        <label for="displayed_price" class="block text-gray-700 font-bold mb-2">Displayed Price:</label>
                        <input type="number" id="displayed_price" name="displayed_price" step="0.01"
                            value="{{ old('displayed_price', $product->displayed_price) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <!-- Discount -->
                    <div class="mb-4">
                        <label for="discount" class="block text-gray-700 font-bold mb-2">Discount (%):</label>
                        <input type="number" id="discount" name="discount" step="0.01"
                            value="{{ old('discount', $product->discount) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <!-- Unit -->
                    <div class="mb-4">
                        <label for="unit" class="block text-gray-700 font-bold mb-2">Unit:</label>
                        <input type="text" id="unit" name="unit" value="{{ old('unit', $product->unit) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700 font-bold mb-2">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <!-- Image Upload -->
                    @if ($product->image)
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Current Image:</label>
                        <img src="{{ asset('storage/' . $product->image) }}"
     alt="Product Image"
     class="w-32 h-32 object-cover rounded-md">

                        {{-- <img src="{{ asset('storage/' . Str::after($product->image, 'public/')) }}" alt="Product Image" class="w-32 h-32 object-cover rounded-md"> --}}
                        {{-- <img src="{{ asset(str_replace('public/', 'storage/', $product->image)) }}"
                        alt="Product Image" class="w-32 h-32 object-cover rounded-md"> --}}

                    </div>
                     @endif

                    <div class="mb-4">
                        <label for="image" class="block text-gray-700 font-bold mb-2">New Image:</label>
                        <input type="file" id="image" name="image" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>


                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
