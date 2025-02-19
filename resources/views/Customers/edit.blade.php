<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Customer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone_number" class="block text-gray-700 font-bold mb-2">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
                        <textarea id="address" name="address" class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('address', $customer->address) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="root" class="block text-gray-700 font-bold mb-2">Street:</label>
                        <input type="text" id="root" name="root" value="{{ old('root', $customer->root) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    {{-- <div class="mb-4">
                        <label for="loan" class="block text-gray-700 font-bold mb-2">Loan (if any):</label>
                        <input type="number" id="loan" name="loan" step="0.01" value="{{ old('loan', $customer->loan) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm">
                    </div> --}}
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
