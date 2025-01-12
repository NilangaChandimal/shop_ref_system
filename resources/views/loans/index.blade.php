<x-app-layout>
    <x-slot name="header">
        <div class="relative flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                {{ $customer->name }}'s Loans
            </h2>
            <div class="bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-md">
                <span class="font-medium">Total Loan Balance: </span>
                <span class="font-bold">Rs.{{ number_format($totalLoanAmount, 2) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Loan Transactions</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($loans as $loan)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            #{{ $loan->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($editLoanId == $loan->id)
                                                <form action="{{ route('loan.update', $loan->id) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number"
                                                           name="amount"
                                                           value="{{ $loan->amount }}"
                                                           class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    >
                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                    >
                                                        Save
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-sm font-medium text-gray-900">
                                                    Rs.{{ number_format($loan->amount, 2) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loan->created_at->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            @if($editLoanId == $loan->id)
                                                <a href="{{ route('loans.index', ['customerId' => $customer->id, 'editLoanId' => null]) }}"
                                                   class="text-gray-600 hover:text-gray-900">
                                                    Cancel
                                                </a>
                                            @else
                                                <a href="{{ route('loans.index', ['customerId' => $customer->id, 'editLoanId' => $loan->id]) }}"
                                                   class="text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </a>
                                            @endif

                                            <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 font-medium"
                                                        onclick="return confirm('Are you sure you want to delete this loan?')"
                                                >
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
