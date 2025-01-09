<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $customer->name }}'s Loans
        </h2>
        <!-- Total Loan Balance (Positioned in the top-right corner) -->
        <div class="absolute top-20 right-4 bg-green-500 text-white px-4 py-2 rounded-lg">
            <span class="font-bold">Total Loan: </span>
            Rs.{{ number_format($totalLoanAmount, 2) }}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                <!-- Loan Table -->
                <h3 class="text-lg font-semibold mb-4">Loan Transactions</h3>
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Loan ID</th>
                            <th class="border px-4 py-2">Amount</th>
                            <th class="border px-4 py-2">Date</th>
                            <th class="border px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            <tr>
                                <td class="border px-4 py-2">{{ $loan->id }}</td>

                                <!-- Display loan amount or the form to edit it -->
                                <td class="border px-4 py-2">
                                    @if($editLoanId == $loan->id) <!-- Check if this loan is being edited -->
                                    <form action="{{ route('loan.update', $loan->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') <!-- Use the PUT method for updating -->
                                        <input type="number" name="amount" value="{{ $loan->amount }}" class="border p-2 rounded">
                                        <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                                    </form>

                                    @else
                                        Rs.{{ number_format($loan->amount, 2) }}
                                    @endif
                                </td>

                                <td class="border px-4 py-2">{{ $loan->created_at->format('Y-m-d') }}</td>
                                <td class="border px-4 py-2">
                                    <!-- Toggle edit form -->
                                    @if($editLoanId == $loan->id)
                                        <a href="{{ route('loans.index', ['customerId' => $customer->id, 'editLoanId' => null]) }}" class="text-red-500">Cancel</a>
                                    @else
                                        <a href="{{ route('loans.index', ['customerId' => $customer->id, 'editLoanId' => $loan->id]) }}" class="text-blue-500">Edit</a>
                                    @endif

                                    <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
