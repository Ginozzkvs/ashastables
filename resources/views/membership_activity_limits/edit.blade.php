@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">

    <h2 class="text-2xl font-bold mb-6">
        Edit Activity Limit
    </h2>

    <form method="POST"
          action="{{ route('membership-activity-limits.update', $limit) }}"
          class="bg-white border p-6 rounded space-y-4">

        @csrf
        @method('PUT')

        {{-- Membership --}}
        <div>
            <label class="block font-semibold mb-1">
                Membership
            </label>
            <select name="membership_id"
                    class="w-full border px-3 py-2 rounded"
                    required>
                @foreach($memberships as $membership)
                    <option value="{{ $membership->id }}"
                        @selected($membership->id === $limit->membership_id)>
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
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}"
                        @selected($activity->id === $limit->activity_id)>
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
                   value="{{ $limit->max_per_year }}"
                   class="w-full border px-3 py-2 rounded"
                   min="1"
                   required>
        </div>

        {{-- Max per day --}}
        <div>
            <label class="block font-semibold mb-1">
                Max Per Day
            </label>
            <input type="number"
                   name="max_per_day"
                   value="{{ $limit->max_per_day }}"
                   class="w-full border px-3 py-2 rounded"
                   min="1"
                   placeholder="Unlimited">
        </div>

        {{-- Buttons --}}
        <div class="flex justify-between pt-4">
            <a href="{{ route('membership-activity-limits.index') }}"
               class="text-gray-600">
                ← Back
            </a>

            <button class="bg-blue-600 text-white px-6 py-2 rounded">
                Update
            </button>
        </div>

    </form>
</div>
@endsection
