<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('POS System') }}
        </h2>
    </x-slot>
    <style>
        #discountModal .scrollable-content {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="mb-6 border-b pb-4">
                    <button type="button" onclick="toggleModal('discountModal')"
                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                        Manage Discounts
                    </button>
                </div>

                <div id="discountModal"
                    class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                        <h3 class="text-lg font-semibold mb-3">Product Discounts</h3>
                        <div class="scrollable-content grid grid-cols-1 gap-4">
                            @foreach ($products as $product)
                                <div class="flex items-center gap-3 border p-3 rounded">
                                    <span class="flex-1">{{ $product->name }}</span>
                                    <input type="number" id="discount-{{ $product->id }}"
                                        value="{{ $product->discount }}"
                                        class="w-20 border rounded px-2 py-1 text-right" min="0" max="100"
                                        step="0.01">
                                    <span class="mr-2">%</span>
                                    <button onclick="updateDiscount({{ $product->id }})"
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Update</button>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button onclick="toggleModal('discountModal')"
                                class="bg-gray-500 text-white px-4 py-2 rounded">Close</button>
                        </div>
                    </div>
                </div>

                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf

                    <div class="mb-4 relative">
                        <label for="customer_search" class="block text-gray-700 font-bold mb-2">Customer:</label>
                        <input type="text" id="customer_search" class="w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Search customer..." autocomplete="off" oninput="searchCustomers(this.value)">
                        <input type="hidden" id="customer_id" name="customer_id">
                        <div id="customer_results"
                            class="hidden absolute z-10 w-full bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
                        </div>
                    </div>

                    <div id="cart" class="mb-4">
                        <label for="products" class="block text-gray-700 font-bold mb-2">Products:</label>

                        <div id="product-section" class="space-y-4">
                            <div class="flex items-center space-x-4" id="product-0">
                                <select name="products[0][product_id]"
                                    class="w-1/2 border-gray-300 rounded-md shadow-sm" required
                                    onchange="calculateTotal()">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}"
                                            data-discount="{{ $product->discount }}">{{ $product->name }} (Dis:
                                            {{ number_format($product->discount) }})</option>
                                    @endforeach
                                </select>
                                <input type="number" name="products[0][quantity]"
                                    class="w-1/4 border-gray-300 rounded-md shadow-sm" min="1" value="1"
                                    required onchange="calculateTotal()">
                                <button type="button" onclick="removeProduct(this)"
                                    class="bg-red-500 text-white p-2 rounded">Remove</button>
                            </div>
                        </div>
                        <button type="button" onclick="addProduct()"
                            class="bg-blue-500 text-white p-2 rounded mt-4">Add Another Product</button>
                    </div>

                    <div class="mb-4">
                        <label for="total_value" class="block text-gray-700 font-bold mb-2">Total Value:</label>
                        <input type="text" id="total_value" name="total_value"
                            class="w-full border-gray-300 rounded-md shadow-sm" readonly>
                    </div>

                    <div class="mb-4">
                        <label for="paid_value" class="block text-gray-700 font-bold mb-2">Paid Value:</label>
                        <input type="number" id="paid_value" name="paid_value"
                            class="w-full border-gray-300 rounded-md shadow-sm" min="0" required
                            onchange="calculateBalance()">
                    </div>

                    <div class="mb-4">
                        <label for="balance" class="block text-gray-700 font-bold mb-2">Balance:</label>
                        <input type="text" id="balance" name="balance"
                            class="w-full border-gray-300 rounded-md shadow-sm" readonly>
                    </div>

                    <div>
                        <button type="button" id="print-bill"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            onclick="printReceipt()">Print Bill</button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Complete Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let customers = {!! json_encode(
            $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'text' => $customer->name . ' - ' . $customer->address . ' - ' . $customer->root,
                ];
            }),
        ) !!};

        function searchCustomers(query) {
            const resultsContainer = document.getElementById('customer_results');
            resultsContainer.innerHTML = '';

            if (!query) {
                resultsContainer.classList.add('hidden');
                return;
            }

            const filtered = customers.filter(customer =>
                customer.text.toLowerCase().includes(query.toLowerCase())
            );

            if (filtered.length > 0) {
                filtered.forEach(customer => {
                    const div = document.createElement('div');
                    div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                    div.textContent = customer.text;
                    div.onclick = () => selectCustomer(customer);
                    resultsContainer.appendChild(div);
                });
                resultsContainer.classList.remove('hidden');
            } else {
                resultsContainer.classList.add('hidden');
            }
        }

        function selectCustomer(customer) {
            document.getElementById('customer_search').value = customer.text;
            document.getElementById('customer_id').value = customer.id;
            document.getElementById('customer_results').classList.add('hidden');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#customer_search')) {
                document.getElementById('customer_results').classList.add('hidden');
            }
        });
        let productCount = 1;

        function addProduct() {
            const productSection = document.getElementById('product-section');
            const newProduct = document.createElement('div');
            newProduct.classList.add('flex', 'items-center', 'space-x-4');

            if (productCount === 1) {
                newProduct.innerHTML = `
                    <select name="products[${productCount}][product_id]" class="w-1/2 border-gray-300 rounded-md shadow-sm" required onchange="calculateTotal()">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-discount="{{ $product->discount }}">{{ $product->name }}  (Dis: {{ number_format($product->discount) }})</option>
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
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">{{ $product->name }} (Dis: {{ number_format($product->discount) }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="products[${productCount}][quantity]" class="w-1/4 border-gray-300 rounded-md shadow-sm" min="1" value="1" required onchange="calculateTotal()">
                    <button type="button" onclick="removeProduct(this)" class="bg-red-500 text-white p-2 rounded">Remove</button>
                `;
            }

            productSection.appendChild(newProduct);
            productCount++;
        }
9
        function removeProduct(button) {
            button.closest('div').remove();
            calculateTotal();
        }

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

        function calculateBalance() {
            const totalValue = parseFloat(document.getElementById('total_value').value) || 0;
            const paidValue = parseFloat(document.getElementById('paid_value').value) || 0;
            const balance = paidValue - totalValue;

            document.getElementById('balance').value = balance.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', calculateTotal);

        function printReceipt() {
            const customerName = document.getElementById('customer_search').value || 'Walk-in Customer';
            const totalValue = document.getElementById('total_value').value;
            const paidValue = document.getElementById('paid_value').value;
            const balance = document.getElementById('balance').value;

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
                <p>Reg.No:kg/05876</p>
                <hr>
                <p><strong>Date:</strong> ${formattedDate}</p>
                <p><strong>Time:</strong> ${formattedTime}</p>
                <small><strong>Customer:</strong> ${customerName}</small>
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

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');

            if (modal.classList.contains('hidden')) {
                location.reload();
            }
        }

        async function updateDiscount(productId) {
            const newDiscount = document.getElementById(`discount-${productId}`).value;

            try {
                const response = await fetch(`/products/${productId}/discount`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        discount: newDiscount
                    })
                });

                if (!response.ok) throw new Error('Update failed');

                document.querySelectorAll(`select[name^="products"] option[value="${productId}"]`).forEach(option => {
                    option.dataset.discount = newDiscount;
                    option.text = `${option.text.split(' (Dis:')[0]} (Dis: ${newDiscount}%)`;
                });

                calculateTotal();

                alert('Discount updated successfully!');
            } catch (error) {
                alert('Error updating discount: ' + error.message);
            }
        }
    </script>
</x-app-layout>
