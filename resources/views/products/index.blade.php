<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center space-x-4">
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Product List') }}
                </h2>
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                    Total Products: {{ $totalProducts }}
                </span>
            </div>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create Product
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <form action="{{ route('products.index') }}" method="GET" class="flex items-center space-x-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by product name"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                        </div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 shadow-sm">
                            Search
                        </button>
                    </form>
                </div>

                <div class="flex justify-end space-x-3 px-6 py-3">
                    <button id="exportExcelBtn"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-sm">
                        Export Excel
                    </button>
                    {{-- <button id="exportPdfBtn"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-sm">
                        Export PDF
                    </button> --}}
                </div>
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data"
                    class="mb-4">
                    @csrf
                    <div class="flex items-center space-x-3">
                        <input type="file" name="file" accept=".xlsx,.xls" required
                            class="border rounded-lg p-2 w-64">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Import Products
                        </button>
                    </div>
                </form>




                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Image</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Original Price</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Displayed Price</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Shop Price
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Discount (%)</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Selling Price</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Profit</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unit</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    {{-- get iteration --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"> {{ $loop->iteration }}</td>
                                    </td>

                                    <!-- Image Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . Str::after($product->image, 'public/')) }}"
                                                alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-md">
                                        @else
                                            <span class="text-gray-500">No Image</span>
                                        @endif
                                    </td>


                                    <!-- Other Columns -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs.
                                        {{ number_format($product->original_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs.
                                        {{ number_format($product->displayed_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs.
                                        {{ number_format($product->shop_price, 0) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ number_format($product->discount, 2) }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs.
                                        {{ number_format($product->selling_price, 0) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="text-sm {{ $product->profit > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            Rs. {{ number_format($product->profit, 0) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->quantity < 10 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $product->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            {{-- Edit --}}
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                {{-- Delete --}}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No
                                        products found with less than 10 quantity.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

        <script>
            document.getElementById("exportExcelBtn").addEventListener("click", async function() {
                const table = document.querySelector("table");
                const wb = XLSX.utils.book_new();
                const ws_data = [];
                const ws_images = [];

                const rows = table.querySelectorAll("tr");
                for (let r = 0; r < rows.length; r++) {
                    const rowData = [];
                    const cells = rows[r].querySelectorAll("th, td");

                    for (let c = 0; c < cells.length; c++) {
                        const img = cells[c].querySelector("img");

                        if (img) {
                            const imgBase64 = await toDataURL(img.src);
                            rowData.push("[Image]");
                            ws_images.push({
                                r,
                                c,
                                data: imgBase64
                            });
                        } else {
                            rowData.push(cells[c].innerText.trim());
                        }
                    }
                    ws_data.push(rowData);
                }

                const ws = XLSX.utils.aoa_to_sheet(ws_data);
                XLSX.utils.book_append_sheet(wb, ws, "Products");

                XLSX.writeFile(wb, "product_list.xlsx");
            });

            function toDataURL(url) {
                return fetch(url)
                    .then(response => response.blob())
                    .then(blob => new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(reader.result);
                        reader.readAsDataURL(blob);
                    }));
            }

            document.getElementById("exportPdfBtn").addEventListener("click", async function() {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF('l', 'pt', 'a4');

                doc.text("Product List", 40, 40);

                const table = document.querySelector("table");
                let startY = 60;

                for (const row of table.querySelectorAll("tr")) {
                    let x = 40;

                    for (const cell of row.querySelectorAll("th, td")) {
                        const img = cell.querySelector("img");

                        if (img) {
                            const base64Img = await toDataURL(img.src);
                            doc.addImage(base64Img, "JPEG", x, startY, 40, 40);
                            x += 50;
                        } else {
                            doc.text(cell.innerText.trim(), x, startY + 15);
                            x += 100;
                        }
                    }
                    startY += 50;
                }

                doc.save("product_list.pdf");
            });
        </script>
    @endpush


</x-app-layout>
