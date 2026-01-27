@extends('layouts.app')

@section('content')
<style>
    * { font-family: 'Inter', sans-serif; }
    h1, h2, h3, h4 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; }
    
    body {
        background: #0f1419;
    }
    
    .header-divider {
        border-color: #d4af37;
    }
    
    .card-base {
        background: #1a1f2e;
        border: 1px solid #d4af37;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    .card-base:hover {
        box-shadow: 0 8px 24px rgba(212, 175, 55, 0.15);
    }
    
    .btn-gold {
        background: #d4af37;
        color: #0f1419;
        font-weight: 600;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        padding: 0.75rem 2rem;
        font-size: 0.875rem;
    }
    
    .btn-gold:hover {
        background: #e6c547;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2);
    }
    
    .btn-outline {
        border: 1px solid #d4af37;
        color: #d4af37;
        background: transparent;
        font-weight: 600;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        display: inline-block;
    }
    
    .btn-outline:hover {
        background: rgba(212, 175, 55, 0.1);
        transform: translateY(-2px);
    }
    
    .btn-danger {
        border: 1px solid #ef4444;
        color: #ef4444;
        background: transparent;
        font-weight: 600;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        cursor: pointer;
    }
    
    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.1);
        transform: translateY(-1px);
    }
    
    .table-header {
        background: rgba(212, 175, 55, 0.1);
        border-bottom: 1px solid #d4af37;
    }
    
    .table-row {
        border-bottom: 1px solid rgba(212, 175, 55, 0.2);
        transition: background 0.3s;
    }
    
    .table-row:hover {
        background: rgba(212, 175, 55, 0.05);
    }
    
    .success-message {
        border: 1px solid #10b981;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-radius: 0;
        padding: 1rem;
        margin-bottom: 2rem;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        border: 1px solid;
        text-transform: uppercase;
    }
    
    .badge-unit {
        border-color: #d4af37;
        color: #d4af37;
        background: rgba(212, 175, 55, 0.1);
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen p-4 sm:p-8" style="background: #0f1419;">
    <div class="max-w-4xl mx-auto">

        <!-- PAGE HEADER -->
        <div class="flex items-start justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-widest font-bold mb-2" style="color: #d4af37;">{{ __('messages.manage_programs') }}</p>
                <h2 class="text-4xl font-bold text-white" style="letter-spacing: -1px; font-family: 'Cormorant Garamond', serif;">{{ __('messages.activities') }}</h2>
                <p style="color: #9ca3af; font-size: 0.875rem; margin-top: 0.5rem;">{{ __('messages.activities_subtitle') }}</p>
            </div>
            <a href="{{ route('activities.create') }}" class="btn-gold">+ {{ __('messages.add_activity') }}</a>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- ACTIVITIES TABLE -->
        <div class="card-base overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="background: #1a1f2e;">
                    <thead>
                        <tr class="table-header">
                            <th class="text-left px-6 py-4 text-xs font-bold tracking-widest uppercase" style="color: #d1d5db;">{{ __('messages.activity_name') }}</th>
                            <th class="text-left px-6 py-4 text-xs font-bold tracking-widest uppercase" style="color: #d1d5db;">{{ __('messages.unit') }}</th>
                            <th class="text-left px-6 py-4 text-xs font-bold tracking-widest uppercase" style="color: #d1d5db;">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-gray-200 font-semibold">{{ $activity->name }}</td>
                            <td class="px-6 py-4">
                                <span class="badge badge-unit">{{ $activity->unit ?? __('messages.session') }}</span>
                            </td>
                            <td class="px-6 py-4 flex gap-3">
                                <a href="{{ route('activities.edit', $activity->id) }}" class="btn-outline">{{ __('messages.edit') }}</a>

                                <form action="{{ route('activities.destroy', $activity->id) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" onclick="return confirm('{{ __('messages.confirm_delete_activity') }}')">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr class="table-row">
                            <td colspan="3" class="px-6 py-4 text-center text-gray-400">{{ __('messages.no_activities_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
