<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
                <p class="mt-1 text-sm text-gray-600">View your business metrics and performance indicators</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md shadow-sm">
                    Last Updated: {{ now()->format('d M Y, H:i') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Key Metrics Section -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Total Customers Card -->
                <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-sm hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Total Customers</p>
                                <p class="text-2xl font-bold text-blue-600">{{ number_format($totalCustomers) }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Products Card -->
                <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-sm hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Total Products</p>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($totalProducts) }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Sales Card -->
                <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-sm hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Total Sales Value</p>
                                <p class="text-2xl font-bold text-purple-600">Rs.{{ number_format($totalSalesValue, 2) }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Profit Card -->
                <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-sm hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Total Profit</p>
                                <p class="text-2xl font-bold text-indigo-600">Rs.{{ number_format($totalProfit, 2) }}</p>
                            </div>
                            <div class="p-3 bg-indigo-100 rounded-full">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily and Monthly Overview Section -->
            <div class="grid gap-6 mb-8 md:grid-cols-2">
                <!-- Daily Overview Card -->
                <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-sm hover:shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Daily Overview</h3>
                            <span class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full">Today</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Today's Sales</p>
                                <p class="mt-2 text-2xl font-bold text-blue-600">Rs.{{ number_format($dailySalesValue, 2) }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Today's Profit</p>
                                <p class="mt-2 text-2xl font-bold text-green-600">Rs.{{ number_format($dailyProfit, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Overview Card -->
                <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-sm hover:shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Monthly Overview</h3>
                            <span class="px-3 py-1 text-xs font-medium text-purple-600 bg-purple-100 rounded-full">{{ now()->format('F') }}</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Monthly Sales</p>
                                <p class="mt-2 text-2xl font-bold text-purple-600">Rs.{{ number_format($monthlySalesValue, 2) }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Monthly Profit</p>
                                <p class="mt-2 text-2xl font-bold text-indigo-600">Rs.{{ number_format($monthlyProfit, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-12 bg-gray-50">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <!-- Key Metrics Section -->
                    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-2">
                        <!-- Total Returns Card -->
                        <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-sm hover:shadow-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-2">
                                        <p class="text-sm font-medium text-gray-500">Total Returns</p>
                                        <p class="text-2xl font-bold text-red-600">Rs. {{ number_format($totalReturns, 2) }}</p>
                                    </div>
                                    <div class="p-3 bg-red-100 rounded-full">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0c0-1.657 1.343-3 3-3s3 1.343 3 3M6 9a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Loans Card -->
                        <div class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-sm hover:shadow-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-2">
                                        <p class="text-sm font-medium text-gray-500">Total Loans</p>
                                        <p class="text-2xl font-bold text-green-600">Rs. {{ number_format($totalLoans, 2) }}</p>
                                    </div>
                                    <div class="p-3 bg-green-100 rounded-full">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 9V7a4 4 0 00-8 0v2m8 0a4 4 0 01-8 0m8 0v6m-8-6v6M8 15H6m12 0h-2m0 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2v-2" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Charts Section -->
            <div class="grid gap-6 mb-8">
                <!-- Sales and Profit Chart -->
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Monthly Sales and Profit Trends</h3>
                    <canvas id="monthlySalesChart" class="w-full h-72"></canvas>
                </div>

                <!-- Product Charts -->
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Top Products Chart -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Top 10 Most Sold Products</h3>
                        <canvas id="topSoldProductsChart" class="w-full"></canvas>
                    </div>

                    <!-- Low Stock Products Chart -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Products with Low Stock</h3>
                        <canvas id="lowStockProductsChart" class="w-full"></canvas>
                    </div>
                </div>
            </div>

            <!-- Customer Loans Table -->
            <div class="p-6 space-y-6 bg-gray-50">
                <!-- Customer Loans Section -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-900">Customer Loans</h3>
                        @if ($customersWithLoans->isEmpty())
                            <p class="text-gray-500">No customers with loans.</p>
                        @endif
                        <div class="mt-4 h-[300px]">
                            <canvas id="loansChart"></canvas>
                        </div>
                        <div class="mt-6 overflow-x-auto">
                            {{-- <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Customer Name</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Loan Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($customersWithLoans as $customer)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $customer->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Rs.{{ number_format($customer->loans->sum('amount'), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table> --}}
                        </div>
                    </div>
                </div>

                <!-- Total Sales Section -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-900">Total Sales per Customer</h3>
                        @if ($customerSalesData->isEmpty())
                            <p class="text-gray-500">No sales data available.</p>
                        @endif
                        <div class="mt-4 h-[300px]">
                            <canvas id="salesChart"></canvas>
                        </div>
                        <div class="mt-6 overflow-x-auto">
                            {{-- <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gradient-to-r from-gray-50 to-white">
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales Value</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($customerSalesData as $index => $customer)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer['customer_name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rs. {{ number_format($customer['total_sales_value'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table> --}}
                        </div>
                    </div>
                </div>

                <!-- Returns Section -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-900">Returns Overview</h3>
                        @if(isset($refundData) && $refundData->isNotEmpty())
                            <div class="mt-4 h-[300px]">
                                <canvas id="returnsChart"></canvas>
                            </div>
                            <div class="mt-6 overflow-x-auto">
                                {{-- <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Refund Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($refundData as $data)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $data->customer_name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">Rs.{{ number_format($data->total_refund, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table> --}}
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">No refund data available.</div>
                        @endif
                    </div>
                </div>
            </div>




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Pie Chart for Top 10 Most Sold Products
        const topSoldProductsCtx = document.getElementById('topSoldProductsChart').getContext('2d');
        const topSoldProductsChart = new Chart(topSoldProductsCtx, {
            type: 'pie',
            data: {
                labels: @json($topSoldProducts->keys()), // Product names
                datasets: [{
                    label: 'Top 10 Most Sold Products',
                    data: @json($topSoldProducts->values()), // Quantities sold
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF5733', '#DAF7A6', '#900C3F', '#581845', '#C70039', '#FFC300', '#FF6347'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' sold';
                            }
                        }
                    }
                }
            }
        });

        // Pie Chart for Products with Low Stock
        const lowStockProductsCtx = document.getElementById('lowStockProductsChart').getContext('2d');
        const lowStockProductsChart = new Chart(lowStockProductsCtx, {
            type: 'pie',
            data: {
                labels: @json($lowStockProducts->pluck('name')), // Product names with low stock
                datasets: [{
                    label: 'Products with Low Stock',
                    data: @json($lowStockProducts->pluck('quantity')), // Quantities in low stock
                    backgroundColor: ['#FF5733', '#FFC300', '#DAF7A6', '#900C3F', '#581845'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' units';
                            }
                        }
                    }
                }
            }
        });

        // Line chart for Monthly Sales and Profit (already added)
        const ctx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesChart = new Chart(ctx, {
            type: 'line', // or 'bar' depending on your preference
            data: {
                labels: @json($labels), // dynamic month labels
                datasets: [{
                    label: 'Monthly Sales',
                    data: @json($monthlySalesData), // dynamic sales data
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                }, {
                    label: 'Monthly Profit',
                    data: @json($monthlyProfitData), // dynamic profit data
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const chartConfig = {
            type: 'bar',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rs.' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        };

        // Loans Chart
        const loansData = @json($customersWithLoans->map(function($customer) {
            return [
                'name' => $customer->name,
                'amount' => $customer->loans->sum('amount')
            ];
        }));

        new Chart(document.getElementById('loansChart'), {
            ...chartConfig,
            data: {
                labels: loansData.map(item => item.name),
                datasets: [{
                    data: loansData.map(item => item.amount),
                    backgroundColor: '#4F46E5',
                    borderRadius: 4
                }]
            }
        });

        // Sales Chart
        const salesData = @json($customerSalesData);

        new Chart(document.getElementById('salesChart'), {
            ...chartConfig,
            data: {
                labels: salesData.map(item => item.customer_name),
                datasets: [{
                    data: salesData.map(item => item.total_sales_value),
                    backgroundColor: '#10B981',
                    borderRadius: 4
                }]
            }
        });

        // Returns Chart
        @if(isset($refundData) && $refundData->isNotEmpty())
            const returnsData = @json($refundData);

            new Chart(document.getElementById('returnsChart'), {
                ...chartConfig,
                data: {
                    labels: returnsData.map(item => item.customer_name),
                    datasets: [{
                        data: returnsData.map(item => item.total_refund),
                        backgroundColor: '#EF4444',
                        borderRadius: 4
                    }]
                }
            });
        @endif
    </script>

</x-app-layout>
