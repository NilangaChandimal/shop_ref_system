<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customer List') }}
            </h2>
            <div class="text-right">
                <span class="text-gray-600 font-bold">Total Customers:</span>
                <span class="text-green-600 font-bold text-lg">{{ $totalCustomers }}</span>
            </div>
        </div>
    </x-slot>

    <div class="mb-4">
        <form action="{{ route('customers.index') }}" method="GET" class="flex items-center">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by customer name"
                class="border border-gray-300 rounded px-4 py-2 mr-2"
            />
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Search
            </button>
        </form>
        <a href="{{ route('customers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create Customer
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">#</th>
                            <th class="border border-gray-300 px-4 py-2">Name</th>
                            <th class="border border-gray-300 px-4 py-2">Phone Number</th>
                            <th class="border border-gray-300 px-4 py-2">Address</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $customer->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $customer->phone_number }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $customer->address }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="{{ route('customers.edit', $customer) }}" class="text-blue-500 hover:underline">Edit</a>

                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure you want to delete this customer?')">
                                            Delete
                                        </button>
                                    </form>

                                    <a href="{{ route('loans.index', ['customerId' => $customer->id]) }}" class="text-green-500 hover:underline ml-4">View Loans</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
