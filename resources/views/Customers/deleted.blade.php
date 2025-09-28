<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Deleted Customers</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <form action="{{ route('customers.deleted') }}" method="GET" class="flex items-center space-x-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search customers by name or phone number"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            />
                        </div>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors duration-200 shadow-sm">
                            Search Customers
                        </button>
                    </form>
                </div>

                @if (session('success'))
                    <div class="m-6">
                        <div class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-green-800 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <div class="max-h-96 overflow-y-auto">
                    @if($deletedCustomers->isEmpty())
                        <p>No deleted customers.</p>
                    @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left">Name</th>
                                    <th class="px-6 py-3 text-left">Phone</th>
                                    <th class="px-6 py-3 text-left">Address</th>
                                    <th class="px-6 py-3 text-left">Root</th>
                                    <th class="px-6 py-3 text-left">Deleted At</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deletedCustomers as $customer)
                                    <tr>
                                        <td class="px-6 py-4">{{ $customer->name }}</td>
                                        <td class="px-6 py-4">{{ $customer->phone_number }}</td>
                                        <td class="px-6 py-4">{{ $customer->address }}</td>
                                        <td class="px-6 py-4">{{ $customer->root }}</td>
                                        <td class="px-6 py-4">{{ $customer->deleted_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('customers.restore', $customer->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-green-600 hover:text-green-800">Restore</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
