@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="flex justify-between mb-4">
        <h2 class="text-2xl font-bold">Memberships</h2>
        <a href="{{ route('memberships.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
           + Add Membership
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Name</th>
                <th class="border p-2">Price</th>
                <th class="border p-2">Duration (days)</th>
                <th class="border p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($memberships as $membership)
            <tr>
                <td class="border p-2">{{ $membership->name }}</td>
                <td class="border p-2">${{ $membership->price }}</td>
                <td class="border p-2">{{ $membership->duration_days }}</td>
                <td class="border p-2 space-x-2">
                    <a href="{{ route('memberships.edit', $membership) }}"
                       class="text-blue-600">Edit</a>

                    <form action="{{ route('memberships.destroy', $membership) }}"
                          method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this membership?')"
                                class="text-red-600">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
