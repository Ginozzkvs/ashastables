@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between mb-4">
        <h2 class="text-2xl font-bold">Activities</h2>
        <a href="{{ route('activities.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            + Add Activity
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Name</th>
                <th class="border p-2">Unit</th>
                <th class="border p-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
            <tr>
                <td class="border p-2">{{ $activity->name }}</td>
                <td class="border p-2">{{ $activity->unit }}</td>
                <td class="border p-2">
                    <a href="{{ route('activities.edit', $activity->id) }}"
                       class="text-blue-600">Edit</a>

                    <form action="{{ route('activities.destroy', $activity->id) }}"
                          method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete?')"
                                class="text-red-600 ml-2">
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
