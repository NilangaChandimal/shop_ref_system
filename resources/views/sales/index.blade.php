<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sales Transactions') }}
            </h2>
            <div class="text-right">
                <span class="text-gray-600 font-bold">Total Sales Value:</span>
                <span class="text-green-600 font-bold text-lg">{{ number_format($totalSalesValue, 2) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">#</th>
                            <th class="border border-gray-300 px-4 py-2">Customer</th>
                            <th class="border border-gray-300 px-4 py-2">Product</th>
                            <th class="border border-gray-300 px-4 py-2">Quantity</th>
                            <th class="border border-gray-300 px-4 py-2">Total Value</th>
                            <th class="border border-gray-300 px-4 py-2">Profit</th>
                            <th class="border border-gray-300 px-4 py-2">Paid Value</th>
                            <th class="border border-gray-300 px-4 py-2">Balance</th>
                            <th class="border border-gray-300 px-4 py-2">Date</th>
                            <th class="border border-gray-300 px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $sale->customer->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @foreach ($sale->products as $product)
                                        {{ $product->name }} (Price: {{ number_format($product->selling_price, 2) }})<br>
                                    @endforeach
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @foreach ($sale->products as $product)
                                        {{ $product->pivot->quantity }}<br>
                                    @endforeach
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @php
                                        $total = 0;
                                        $profit = 0;
                                        foreach ($sale->products as $product) {
                                            $total += $product->pivot->quantity * $product->selling_price;
                                            $profit += $product->pivot->quantity * ($product->selling_price - $product->original_price);
                                        }
                                    @endphp
                                    {{ number_format($total, 2) }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ number_format($profit, 2) }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ number_format($sale->paid_value, 2) }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ number_format($sale->balance, 2) }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <form action="{{ route('returns.create', $sale->id) }}" method="GET">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Return
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">No sales found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
