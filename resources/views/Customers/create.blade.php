<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Customer') }}
        </h2>
    </x-slot>
    @if ($errors->any())
    <div class="text-red-600">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone_number" class="block text-gray-700 font-bold mb-2">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
                        <textarea id="address" name="address" class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="root" class="block text-gray-700 font-bold mb-2">Street:</label>
                        <input type="text" id="root" name="root" value="{{ old('root') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    {{-- <div class="mb-4">
                        <label for="loan" class="block text-gray-700 font-bold mb-2">Loan (if any):</label>
                        <input type="number" id="loan" name="loan" step="0.01" value="{{ old('loan') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm">
                    </div> --}}
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
