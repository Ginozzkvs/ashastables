@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">

    <h2 class="text-2xl font-bold mb-6">
        Add Activity Limit
    </h2>

    <form method="POST"
          action="{{ route('membership-activity-limits.store') }}"
          class="bg-white border p-6 rounded space-y-4">

        @csrf

        {{-- Membership --}}
        <div>
            <label class="block font-semibold mb-1">
                Membership
            </label>
            <select name="membership_id"
                    class="w-full border px-3 py-2 rounded"
                    required>
                <option value="">-- Select Membership --</option>
                @foreach($memberships as $membership)
                    <option value="{{ $membership->id }}">
                        {{ $membership->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Activity --}}
        <div>
            <label class="block font-semibold mb-1">
                Activity
            </label>
            <select name="activity_id"
                    class="w-full border px-3 py-2 rounded"
                    required>
                <option value="">-- Select Activity --</option>
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">
                        {{ $activity->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Max per year --}}
        <div>
            <label class="block font-semibold mb-1">
                Max Per Year
            </label>
            <input type="number"
                   name="max_per_year"
                   class="w-full border px-3 py-2 rounded"
                   min="1"
                   required>
        </div>

        {{-- Max per day --}}
        <div>
            <label class="block font-semibold mb-1">
                Max Per Day (optional)
            </label>
            <input type="number"
                   name="max_per_day"
                   class="w-full border px-3 py-2 rounded"
                   min="1"
                   placeholder="Leave empty for unlimited">
        </div>

        {{-- Buttons --}}
        <div class="flex justify-between pt-4">
            <a href="{{ route('membership-activity-limits.index') }}"
               class="text-gray-600">
                ← Back
            </a>

            <button class="bg-blue-600 text-white px-6 py-2 rounded">
                Save
            </button>
        </div>

    </form>
</div>
@endsection
