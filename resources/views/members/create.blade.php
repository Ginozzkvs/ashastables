@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Add New Member</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('members.store') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf

    <!-- Scan -->
    <div class="mb-4">
        <label class="block mb-1 font-medium">Scan RFID</label>
        <input type="text" name="card_uid" class="w-full border border-gray-300 rounded px-3 py-2" autofocus placeholder="Tap card here" required>
    </div>

    <!-- Name -->
    <div class="mb-4">
        <label class="block mb-1 font-medium">Name</label>
        <input type="text" name="name" value="{{ old('name') }}"
            class="w-full border border-gray-300 rounded px-3 py-2" required>
    </div>

    <!-- Phone -->
    <div class="mb-4">
        <label class="block mb-1 font-medium">Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}"
            class="w-full border border-gray-300 rounded px-3 py-2" required>
    </div>

    <!-- Membership -->
    <div class="mb-4">
        <label class="block mb-1 font-medium">Membership</label>
        <select name="membership_id"
            class="w-full border border-gray-300 rounded px-3 py-2" required>
            <option value="">-- Select Membership --</option>
            @foreach ($memberships as $membership)
                <option value="{{ $membership->id }}">
                    {{ $membership->name }} ({{ $membership->duration_days }} days)
                </option>
            @endforeach
        </select>
    </div>

    <!-- Active -->
    <div class="mb-4 flex items-center">
        <input type="checkbox" name="active" value="1" checked class="mr-2">
        <label class="font-medium">Active</label>
    </div>

    <button type="submit"
        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
        Add Member
    </button>
</form>
 
</div>
@endsection
