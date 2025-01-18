<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight tracking-wide">
                {{ __('All Products') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="bg-white shadow-lg rounded-xl p-6 mb-8 transform transition-all duration-300 hover:shadow-xl">
                <form action="{{ route('products.display') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex-1 relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by product name"
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-gray-700 placeholder-gray-400"
                        />
                    </div>
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search Products
                    </button>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($products as $product)
                        <div class="group bg-white border-2 border-gray-100 rounded-xl overflow-hidden shadow-md transition-all duration-300 hover:shadow-xl hover:border-blue-200 transform hover:-translate-y-1">
                            <!-- Product Image Container -->
                            <div class="bg-gray-50 flex items-center justify-center h-56 overflow-hidden">
                                @if ($product->image)
                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="h-full w-full object-contain transform transition-transform duration-300 group-hover:scale-105"
                                    >
                                @else
                                    <div class="flex items-center justify-center w-full h-full bg-gray-100">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                                    {{ $product->name }}
                                </h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 font-medium">Selling Price</span>
                                    <span class="text-lg font-bold text-blue-600">
                                        Rs.{{ number_format($product->selling_price, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-16">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8-8v16m9-8a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">No products available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
