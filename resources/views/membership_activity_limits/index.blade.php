@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">

    <div class="flex justify-between mb-4">
        <h2 class="text-2xl font-bold">Membership Activity Limits</h2>

        <a href="{{ route('membership-activity-limits.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            + Add Limit
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
                <th class="border p-2 text-left">Membership</th>
                <th class="border p-2 text-left">Activity</th>
                <th class="border p-2 text-center">Max / Year</th>
                <th class="border p-2 text-center">Max / Day</th>
                <th class="border p-2 text-center">Action</th>
            </tr>
        </thead>

        <tbody>
        @forelse($limits as $limit)
            <tr>
                <td class="border p-2">
                    {{ $limit->membership->name }}
                </td>

                <td class="border p-2">
                    {{ $limit->activity->name }}
                </td>

                <td class="border p-2 text-center">
                    {{ $limit->max_per_year }}
                </td>

                <td class="border p-2 text-center">
                    {{ $limit->max_per_day ?? 'Unlimited' }}
                </td>

                <td class="border p-2 text-center space-x-2">
                    <a href="{{ route('membership-activity-limits.edit', $limit) }}"
                       class="text-blue-600">
                        Edit
                    </a>

                    <form action="{{ route('membership-activity-limits.destroy', $limit) }}"
                          method="POST"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this limit?')"
                                class="text-red-600">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="border p-4 text-center text-gray-500">
                    No activity limits found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
@endsection
