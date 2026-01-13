@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">

    <form method="POST"
          action="{{ route('members.update', $member->id) }}"
          class="bg-white p-6 shadow rounded">

        @csrf
        @method('PUT')

        <h2 class="text-xl font-semibold mb-6">Edit Member</h2>

        <!-- Name -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $member->name) }}"
                class="w-full border rounded px-3 py-2"
                required
            >
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Phone</label>
            <input
                type="text"
                name="phone"
                value="{{ old('phone', $member->phone) }}"
                class="w-full border rounded px-3 py-2"
            >
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email', $member->email) }}"
                class="w-full border rounded px-3 py-2"
            >
        </div>

        <!-- Card UID -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Card UID</label>
            <input
                type="text"
                name="card_uid"
                value="{{ old('card_uid', $member->card_uid) }}"
                class="w-full border rounded px-3 py-2"
                placeholder="Tap NFC card or enter UID"
            >
        </div>

        <!-- Membership -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Membership</label>
            <select
                name="membership_id"
                class="w-full border rounded px-3 py-2"
                required
            >
                <option value="">-- Select Membership --</option>
                @foreach($memberships as $membership)
                    <option value="{{ $membership->id }}"
                        {{ old('membership_id', $member->membership_id) == $membership->id ? 'selected' : '' }}>
                        {{ $membership->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-semibold mb-1">Start Date</label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ old('start_date', $member->start_date) }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
            </div>

            <div>
                <label class="block font-semibold mb-1">End Date</label>
                <input
                    type="date"
                    name="end_date"
                    value="{{ old('end_date', $member->end_date) }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
            </div>
        </div>

        <!-- Active -->
        <div class="mb-6 flex items-center gap-2">
            <input
                type="checkbox"
                name="active"
                id="active"
                class="rounded border-gray-300"
                {{ old('active', $member->active) ? 'checked' : '' }}
            >
            <label for="active" class="font-semibold">Active Member</label>
        </div>

        <!-- Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('members.index') }}"
               class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancel
            </a>

            <button
                type="submit"
                class="px-6 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                Update Member
            </button>
        </div>

    </form>

</div>
@endsection
