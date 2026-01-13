@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8">
   <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Staff Scan
    </h1>

        <a href="{{ route('members.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add Member
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Phone</th>
                    <th class="p-3 text-left">Membership</th>
                    <th class="p-3 text-left">Period</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr class="border-t">
                        <td class="p-3">{{ $member->id }}</td>
                        <td class="p-3 font-medium">{{ $member->name }}</td>
                        <td class="p-3">{{ $member->email }}</td>
                        <td class="p-3">{{ $member->phone }}</td>

                        <td class="p-3">
                            {{ optional($member->membership)->name ?? '-' }}
                        </td>

                        <td class="p-3 text-sm text-gray-600">
                            {{ $member->start_date }} <br>
                            → {{ $member->end_date }}
                        </td>

                        <td class="p-3 text-center">
                            @if($member->active)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td class="p-3 text-center">
                            <a href="{{ route('members.edit', $member) }}"
                               class="text-blue-600 hover:underline mr-3">
                                Edit
                            </a>

                            <form action="{{ route('members.destroy', $member) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Delete this member?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">
                            No members found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
