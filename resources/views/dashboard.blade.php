<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Customers -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700">Total Customers</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $totalCustomers }}</p>
                </div>

                <!-- Total Products -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700">Total Products</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $totalProducts }}</p>
                </div>

                <!-- Total Sales Value -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700">Total Sales Value</h3>
                    <p class="text-2xl font-bold text-blue-600">Rs.{{ number_format($totalSalesValue, 2) }}</p>
                </div>

                <!-- Total Profit -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700">Total Profit</h3>
                    <p class="text-2xl font-bold text-blue-600">Rs.{{ number_format($totalProfit, 2) }}</p>
                </div>

                <!-- Today's Sales Value -->
                <div class="p-6 bg-white rounded-lg shadow col-span-2">
                    <h3 class="text-gray-600 font-bold text-lg">Today's Sales Value</h3>
                    <p class="text-blue-600 text-2xl font-bold">Rs.{{ number_format($dailySalesValue, 2) }}</p>
                </div>

                <!-- Today's Profit -->
                <div class="p-6 bg-white rounded-lg shadow col-span-2">
                    <h3 class="text-gray-600 font-bold text-lg">Today's Profit</h3>
                    <p class="text-blue-600 text-2xl font-bold">Rs.{{ number_format($dailyProfit, 2) }}</p>
                </div>

                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-gray-600 font-bold text-lg">Monthly Sales</h3>
                    <p class="text-2xl font-bold text-blue-600">Rs.{{ number_format($monthlySalesValue, 2) }}</p>
                </div>

                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-gray-600 font-bold text-lg">Monthly Profit</h3>
                    <p class="text-2xl font-bold text-blue-600">Rs.{{ number_format($monthlyProfit, 2) }}</p>
                </div>
            </div>

            <!-- Customer Loans Table -->
            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="text-gray-600 font-bold text-lg">Customer Loans</h3>
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Customer Name</th>
                            <th class="border px-4 py-2">Total Loan Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customersWithLoans as $customer)
                            <tr>
                                <td class="border px-4 py-2">{{ $customer->name }}</td>
                                <td class="border px-4 py-2">Rs.{{ number_format($customer->loans->sum('amount'), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pie Charts for Top Sold Products and Low Stock Products -->

            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="text-gray-600 font-bold text-lg">Top 10 Most Sold Products</h3>
                <canvas id="topSoldProductsChart" width="10" height="10"></canvas> <!-- Smaller size -->
            </div>

            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="text-gray-600 font-bold text-lg">Products with Low Stock</h3>
                <canvas id="lowStockProductsChart" width="300" height="300"></canvas> <!-- Smaller size -->
            </div>

            <!-- Monthly Sales and Profit Chart -->
            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="text-gray-600 font-bold text-lg">Monthly Sales and Profit</h3>
                <canvas id="monthlySalesChart" width="400" height="200"></canvas>
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
    </script>

</x-app-layout>
