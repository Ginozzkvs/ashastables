@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Create Membership</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('memberships.store') }}" method="POST"
          class="bg-white p-6 rounded shadow">
        @csrf

        <!-- Membership Name -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Membership Name</label>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   class="w-full border border-gray-300 rounded px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Price -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Price</label>
            <input type="number"
                   name="price"
                   step="0.01"
                   value="{{ old('price') }}"
                   required
                   class="w-full border border-gray-300 rounded px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Duration -->
        <div class="mb-6">
            <label class="block mb-1 font-medium">Duration (Days)</label>
            <input type="number"
                   name="duration_days"
                   value="{{ old('duration_days') }}"
                   required
                   class="w-full border border-gray-300 rounded px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded
                           hover:bg-blue-700 transition">
                Save Membership
            </button>

            <a href="{{ route('memberships.index') }}"
               class="bg-gray-200 text-gray-800 px-6 py-2 rounded
                      hover:bg-gray-300 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
