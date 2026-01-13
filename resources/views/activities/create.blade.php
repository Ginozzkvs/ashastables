@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Add Activity</h2>

    <form method="POST"
          action="{{ route('activities.store') }}"
          class="bg-white p-6 shadow rounded">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">Activity Name</label>
            <input type="text" name="name"
                   class="w-full border px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Unit</label>
            <select name="unit" class="w-full border px-3 py-2" required>
                <option value="minutes">Minutes</option>
                <option value="times">Times</option>
            </select>
        </div>

        <button class="bg-blue-600 text-white px-6 py-2 rounded">
            Save
        </button>
    </form>
</div>
@endsection
