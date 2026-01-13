@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Edit Membership</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('memberships.update', $membership->id) }}"
          class="bg-white p-6 shadow rounded">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $membership->name) }}"
                class="w-full border rounded px-3 py-2"
                required
            >
        </div>

        <!-- Price -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Price</label>
            <input
                type="number"
                name="price"
                step="0.01"
                value="{{ old('price', $membership->price) }}"
                class="w-full border rounded px-3 py-2"
                required
            >
        </div>

        <!-- Duration -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Duration (days)</label>
            <input
                type="number"
                name="duration_days"
                value="{{ old('duration_days', $membership->duration_days) }}"
                class="w-full border rounded px-3 py-2"
                required
            >
        </div>

        <button class="bg-blue-600 text-white px-6 py-2 rounded">
            Update
        </button>

        <a href="{{ route('memberships.index') }}"
           class="ml-3 text-gray-600 underline">
            Cancel
        </a>
    </form>
</div>
@endsection
