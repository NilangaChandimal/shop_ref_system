<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Return') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('returns.store', $sale->id) }}" method="POST">
                    @csrf

                    <!-- Products to Return -->
                    <div>
                        @foreach ($products as $product)
                            <div class="mb-4 flex items-center space-x-4">
                                <label for="product_{{ $product->id }}" class="block text-gray-700">{{ $product->name }}</label>
                                <input type="number"
                                       id="product_{{ $product->id }}"
                                       name="products[{{ $product->id }}]"
                                       min="0"
                                       max="{{ $product->pivot->quantity }}"
                                       value="0"
                                       class="w-1/4 border-gray-300 rounded-md shadow-sm"
                                       placeholder="Quantity">
                                <span class="text-sm text-gray-500">Max: {{ $product->pivot->quantity }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Buttons -->
                    <div>
                        <!-- Button to process return with quantity and profit adjustment -->
                        <button type="submit" name="action" value="adjust_quantity_and_profit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Process Return (Quantity & Profit Adjusted)
                        </button>

                        <!-- Button to process return with profit adjustment only -->
                        <button type="submit" name="action" value="adjust_profit_only" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Process Return (Profit Adjusted Only)
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
