<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center px-6 py-6 bg-white shadow-sm rounded-lg">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                {{ __('Sales Transactions') }}
            </h2>
            <div class="flex items-center space-x-2 bg-gradient-to-r from-emerald-50 to-white px-8 py-4 rounded-lg border border-emerald-100">
                <span class="text-gray-700 font-medium">Total Sales Value:</span>
                <span class="text-emerald-600 font-bold text-xl" id="totalSalesValue">Rs. 0.00</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="mb-4 flex space-x-4">
                        <input type="date" id="fromDate" class="px-4 py-2 border rounded-lg" onchange="filterSales()">
                        <input type="date" id="toDate" class="px-4 py-2 border rounded-lg" onchange="filterSales()">
                        <label for="customerSelect" class="block text-sm font-medium text-gray-700">Select Customer</label>
                        <select id="customerSelect" class="px-4 py-2 border rounded-lg w-full" onchange="filterSales()">
                            <option value="">Select a customer</option>
                            @foreach ($sales->pluck('customer.name')->unique() as $customerName)
                                <option value="{{ $customerName }}">{{ $customerName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Download Excel Button -->
                    <div class="flex justify-end mb-4">
                        <button onclick="downloadExcel()"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">
                            Download Excel
                        </button>
                    </div>

                    <div class="mb-4">
                        <input type="text" id="searchInput" placeholder="Search sales..." class="px-4 py-2 border rounded-lg w-full" onkeyup="filterSales()">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-50 to-white">
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody id="salesTable" class="bg-white divide-y divide-gray-200">
                                @forelse ($sales->sortByDesc('created_at') as $sale)
                                    @php
                                        $totalValue = $sale->products->sum(fn($product) => $product->pivot->quantity * $product->selling_price);
                                    @endphp
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $sale->customer->name }}-{{ $sale->customer->root }}</td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                @foreach ($sale->products as $product)
                                                    <div class="text-sm"><span class="text-gray-900 font-medium">{{ $product->name }}</span></div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            @foreach ($sale->products as $product)
                                                <div>{{ $product->pivot->quantity }}</div>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 total-value">Rs. {{ number_format($totalValue, 2) }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-emerald-600">
                                            Rs. {{ number_format($sale->products->sum(fn($product) => $product->pivot->quantity * ($product->selling_price - $product->original_price)), 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $sale->created_at }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <form action="{{ route('returns.create', $sale->id) }}" method="GET">
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                                                    Return
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No sales found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100">
                                    <td colspan="4" class="px-6 py-4 text-right font-bold">Total Sales Sum:</td>
                                    <td id="totalSalesSum" class="px-6 py-4 text-left font-bold text-emerald-600">Rs. 0.00</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            filterSales();
        });

        function filterSales() {
            const fromDate = document.getElementById("fromDate").value;
            const toDate = document.getElementById("toDate").value;
            const selectedCustomer = document.getElementById("customerSelect").value.toLowerCase();
            const searchQuery = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("#salesTable tr");
            let totalSalesSum = 0;

            const parseDate = (dateStr) => {
                const date = new Date(dateStr);
                date.setHours(0, 0, 0, 0); // Set time to 00:00:00 for comparison
                return date;
            };

            const from = fromDate ? parseDate(fromDate) : null;
            const to = toDate ? parseDate(toDate) : null;

            rows.forEach(row => {
                const customerName = row.cells[1]?.textContent.toLowerCase() || "";
                const product = row.cells[2]?.textContent.toLowerCase() || "";
                const dateCell = row.cells[6]?.textContent.trim() || "";
                const saleDate = new Date(dateCell);
                saleDate.setHours(0, 0, 0, 0); // Ensure the sale date has no time component
                const totalValue = parseFloat(row.cells[4]?.textContent.replace('Rs.', '').replace(',', '').trim()) || 0;

                // If "from" and "to" dates are the same, include the full day for that date.
                if (from && to && from.getTime() === to.getTime()) {
                    to.setHours(23, 59, 59, 999); // Set the "to" date to the end of that day
                }

                if ((from && saleDate < from) || (to && saleDate > to)) {
                    row.style.display = "none";
                } else if (
                    (selectedCustomer && !customerName.includes(selectedCustomer)) ||
                    (searchQuery && !customerName.includes(searchQuery) && !product.includes(searchQuery))
                ) {
                    row.style.display = "none";
                } else {
                    row.style.display = "";
                    totalSalesSum += totalValue;
                }
            });

            document.getElementById('totalSalesSum').textContent = `Rs. ${totalSalesSum.toFixed(2)}`;
            document.getElementById('totalSalesValue').textContent = `Rs. ${totalSalesSum.toFixed(2)}`;
        }

        function downloadExcel() {
            const selectedCustomer = document.getElementById('customerSelect').value.toLowerCase();
            const table = document.getElementById('salesTable');
            const rows = Array.from(table.querySelectorAll('tbody tr')).filter(row => row.style.display !== "none");

            const data = [['Customer', 'Product', 'Qty', 'Total Value', 'Profit', 'Date']];

            rows.forEach(row => {
                const customer = row.cells[1]?.textContent.trim() || "";
                const products = Array.from(row.cells[2]?.querySelectorAll('div') || []).map(div => div.textContent.trim()).join(', ');
                const quantities = Array.from(row.cells[3]?.querySelectorAll('div') || []).map(div => div.textContent.trim()).join(', ');
                const totalValue = row.cells[4]?.textContent.trim() || "";
                const profit = row.cells[5]?.textContent.trim() || "";
                const date = row.cells[6]?.textContent.trim() || "";

                if (!selectedCustomer || customer.toLowerCase().includes(selectedCustomer)) {
                    data.push([customer, products, quantities, totalValue, profit, date]);
                }
            });

            const worksheet = XLSX.utils.aoa_to_sheet(data);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Filtered Sales Transactions');

            XLSX.writeFile(workbook, 'Filtered_Sales_Transactions.xlsx');
        }
    </script>



</x-app-layout>
