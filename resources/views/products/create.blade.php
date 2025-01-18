<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="original_price" class="block text-gray-700">Original Price</label>
                        <input type="number" name="original_price" id="original_price" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="displayed_price" class="block text-gray-700">Displayed Price</label>
                        <input type="number" name="displayed_price" id="displayed_price" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="discount" class="block text-gray-700">Discount (%)</label>
                        <input type="number" name="discount" id="discount" step="0.01" max="100" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="unit" class="block text-gray-700">Unit</label>
                        <input type="text" name="unit" id="unit" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="image" class="block text-gray-700">Product Image</label>
                        <input type="file" name="image" id="image" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                            Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
