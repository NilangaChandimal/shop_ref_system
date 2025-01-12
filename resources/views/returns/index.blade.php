<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Return Details') }}
            </h2>
            <div class="bg-gradient-to-r from-emerald-50 to-white px-6 py-3 rounded-lg shadow-sm border border-emerald-100">
                <span class="text-gray-700 font-medium">Total Return Value:</span>
                <span class="text-emerald-600 font-bold text-xl">
                    Rs. {{ number_format($totalReturnValue, 2) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price per Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Refund</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($returns as $return)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $return->customer_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $return->product_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $return->returned_quantity }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Rs. {{ number_format($return->price_per_unit, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Rs. {{ number_format($return->returned_quantity * $return->price_per_unit, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $return->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No return details available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="p-6 bg-gray-50">
                    {{ $returns->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
