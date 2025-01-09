<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('POS System') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf
                    <!-- Customer Selection -->
                    <div class="mb-4">
                        <label for="customer_id" class="block text-gray-700 font-bold mb-2">Customer:</label>
                        <select id="customer_id" name="customer_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Products Selection (Cart) -->
                    <div id="cart" class="mb-4">
                        <label for="products" class="block text-gray-700 font-bold mb-2">Products:</label>

                        <div id="product-section" class="space-y-4">
                            <!-- Dynamic Product Entries -->
                            <div class="flex items-center space-x-4" id="product-0">
                                <select name="products[0][product_id]" class="w-1/2 border-gray-300 rounded-md shadow-sm" required onchange="calculateTotal()">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-discount="{{ $product->discount }}">{{ $product->name }} ({{ number_format($product->selling_price, 2) }}) (Dis: {{ number_format($product->discount) }})</option>
                                    @endforeach
                                </select>
                                <input type="number" name="products[0][quantity]" class="w-1/4 border-gray-300 rounded-md shadow-sm" min="1" value="1" required onchange="calculateTotal()">
                                <button type="button" onclick="removeProduct(this)" class="bg-red-500 text-white p-2 rounded">Remove</button>
                            </div>
                        </div>
                        <button type="button" onclick="addProduct()" class="bg-blue-500 text-white p-2 rounded mt-4">Add Another Product</button>
                    </div>

                    <!-- Total Section -->
                    <div class="mb-4">
                        <label for="total_value" class="block text-gray-700 font-bold mb-2">Total Value:</label>
                        <input type="text" id="total_value" name="total_value" class="w-full border-gray-300 rounded-md shadow-sm" readonly>
                    </div>

                    <!-- Payment Section -->
                    <div class="mb-4">
                        <label for="paid_value" class="block text-gray-700 font-bold mb-2">Paid Value:</label>
                        <input type="number" id="paid_value" name="paid_value" class="w-full border-gray-300 rounded-md shadow-sm" min="0" required onchange="calculateBalance()">
                    </div>

                    <div class="mb-4">
                        <label for="balance" class="block text-gray-700 font-bold mb-2">Balance:</label>
                        <input type="text" id="balance" name="balance" class="w-full border-gray-300 rounded-md shadow-sm" readonly>
                    </div>

                    <div>
                        <button type="button" id="print-bill" class="btn btn-primary" onclick="printReceipt()">Print Bill</button>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Complete Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS for dynamic product addition/removal and calculation -->
    <script>
        let productCount = 1;

        // Add a product entry
        function addProduct() {
            const productSection = document.getElementById('product-section');
            const newProduct = document.createElement('div');
            newProduct.classList.add('flex', 'items-center', 'space-x-4');

            // Only the first product gets the discount
            if (productCount === 1) {
                newProduct.innerHTML = `
                    <select name="products[${productCount}][product_id]" class="w-1/2 border-gray-300 rounded-md shadow-sm" required onchange="calculateTotal()">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-discount="{{ $product->discount }}">{{ $product->name }} ({{ number_format($product->selling_price, 2) }}) (Dis: {{ number_format($product->discount) }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="products[${productCount}][quantity]" class="w-1/4 border-gray-300 rounded-md shadow-sm" min="1" value="1" required onchange="calculateTotal()">
                    <button type="button" onclick="removeProduct(this)" class="bg-red-500 text-white p-2 rounded">Remove</button>
                `;
            } else {
                newProduct.innerHTML = `
                    <select name="products[${productCount}][product_id]" class="w-1/2 border-gray-300 rounded-md shadow-sm" required onchange="calculateTotal()">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">{{ $product->name }} ({{ number_format($product->selling_price, 2) }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="products[${productCount}][quantity]" class="w-1/4 border-gray-300 rounded-md shadow-sm" min="1" value="1" required onchange="calculateTotal()">
                    <button type="button" onclick="removeProduct(this)" class="bg-red-500 text-white p-2 rounded">Remove</button>
                `;
            }

            productSection.appendChild(newProduct);
            productCount++;
        }

        // Remove a product entry
        function removeProduct(button) {
            button.closest('div').remove();
            calculateTotal();
        }

        // Calculate the total value of the cart
        function calculateTotal() {
            let totalValue = 0;
            const productFields = document.querySelectorAll('select[name^="products["]');
            productFields.forEach(select => {
                const price = parseFloat(select.options[select.selectedIndex].dataset.price);
                const quantity = parseInt(select.closest('.flex').querySelector('input').value);
                totalValue += price * quantity;
            });

            const paidValue = parseFloat(document.getElementById('paid_value').value) || 0;
            const balance = paidValue - totalValue;

            document.getElementById('total_value').value = totalValue.toFixed(2);
            document.getElementById('balance').value = balance.toFixed(2);
        }

        // Calculate the balance when paid value is entered
        function calculateBalance() {
            const totalValue = parseFloat(document.getElementById('total_value').value) || 0;
            const paidValue = parseFloat(document.getElementById('paid_value').value) || 0;
            const balance = paidValue - totalValue;

            document.getElementById('balance').value = balance.toFixed(2);
        }

        // Initial calculation when the page loads
        document.addEventListener('DOMContentLoaded', calculateTotal);

        // Function to print the receipt
        function printReceipt() {
    const customerName = document.getElementById('customer_id').options[document.getElementById('customer_id').selectedIndex].text;
    const totalValue = document.getElementById('total_value').value;
    const paidValue = document.getElementById('paid_value').value;
    const balance = document.getElementById('balance').value;

    // Get the current date and time
    const now = new Date();
    const formattedDate = now.toLocaleDateString();
    const formattedTime = now.toLocaleTimeString();

    let receiptContent = `
        <html>
        <head>
            <title>POS System - Receipt</title>
            <style>
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
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                    font-size: 12px;
                }
                table, th, td {
                    border: 1px solid white;
                }
                th, td {
                    padding: 5px;
                    text-align: left;
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
            </style>
        </head>
        <body>
            <div class="header">
                <img src="{{ asset('images/mc.png') }}" alt="MC PRODUCT Logo" class="logo">
                <p>** MC Product **</p>
                <p>Address: G 10/3/1 Hemmathagama road, Mawanalla</p>
                <p>Tel: 0758933078</p>
                <hr>
                <p><strong>Date:</strong> ${formattedDate}</p>
                <p><strong>Time:</strong> ${formattedTime}</p>
                <hr>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>`;

    // Dynamically add each product row to the table
    const productFields = document.querySelectorAll('select[name^="products["]');
    productFields.forEach((select) => {
        const productName = select.options[select.selectedIndex].text;
        const price = parseFloat(select.options[select.selectedIndex].dataset.price);
        const quantity = parseInt(select.closest('.flex').querySelector('input').value);
        const total = (price * quantity).toFixed(2);

        receiptContent += `
            <tr>
                <td>${productName}</td>
                <td>${quantity}</td>
                <td>Rs.${price.toFixed(2)}</td>
                <td>Rs.${total}</td>
            </tr>`;
    });

    receiptContent += `
                </tbody>
            </table>
            <p><strong>Total Value:</strong> Rs.${totalValue}</p>
            <p><strong>Paid Value:</strong> Rs.${paidValue}</p>
            <p><strong>Balance:</strong> Rs.${balance}</p>
            <div class="footer">
                <p>Thank you for your purchase!</p>
                <p>Visit again soon!</p>
            </div>
        </body>
        </html>
    `;

    const printWindow = window.open('', '', 'width=600,height=600');
    printWindow.document.write(receiptContent);
    printWindow.document.close();
    printWindow.print();
}


    </script>
</x-app-layout>
