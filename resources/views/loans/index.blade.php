<x-app-layout>
    {{-- <style>
        body {
            font-family: Arial, sans-serif;
            width: 2.5in;
            margin: 0;
            padding: 10px;
        }
        h1, p {
            text-align: center;
            margin: 5px 0;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 12px;
        }
        .logo {
            display: block;
            margin: 0 auto;
            width: 80px;
            height: 80px;
        }
    </style> --}}
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

                    <div class="mt-8">
                        <button onclick="printCustomerLoans()" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-md hover:bg-indigo-700">
                            Print Summary
                        </button>
                    </div>

                    <!-- Printable Section -->
<div id="print-area" class="hidden">
    <div style="font-family: Arial, sans-serif; width: 2.5in; margin: 0; padding: 10px;">
        <div class="header" style="text-align: center; margin-bottom: 10px;">
            <img src="{{ asset('images/mc.png') }}" alt="MC PRODUCT Logo"
                 style="display: block; margin: 0 auto; width: 80px; height: 80px;">
            <p style="text-align: center; margin: 5px 0;"><strong>** MC Product **</strong></p>
            <p style="text-align: center; margin: 5px 0;">Address: G 10/3/1 Hemmathagama road, Mawanalla</p>
            <p style="text-align: center; margin: 5px 0;">Tel: 0758933078</p>
            <p style="text-align: center; margin: 5px 0;">Reg.No:kg/05876</p>
            <hr>
            <p style="text-align: center; margin: 5px 0;"><strong>Date:</strong> {{ date('Y-m-d') }}</p>
            <p style="text-align: center; margin: 5px 0;"><strong>Time:</strong> {{ date('H:i:s') }}</p>
            <p style="text-align: center; margin: 5px 0;"><strong>Customer:</strong> {{ $customer->name }}</p>
            <hr>
        </div>
        <hr>
            @foreach ($loans as $loan)


        <p style="text-align: center; margin-top: 20px; font-weight: bold;">Total Loan Balance: Rs.{{ number_format($totalLoanAmount, 2) }} - ({{ $loan->created_at->format('Y-m-d') }})</p>
        @endforeach
        <div class="footer" style="margin-top: 15px; text-align: center; font-size: 12px;">
            <p>Thank you for your purchase!</p>
            <p>Visit again soon!</p>
        </div>
    </div>
</div>




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

    <script>
        function printCustomerLoans() {
            const printContents = document.getElementById('print-area').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // To restore event listeners
        }
    </script>

</x-app-layout>
