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
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.1);
    }
    
    .table-wrapper {
        background: #1a1f2e;
        border: 1px solid #d4af37;
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead {
        background: rgba(212, 175, 55, 0.05);
        border-bottom: 2px solid #d4af37;
    }
    
    .data-table th {
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #d4af37;
        text-transform: uppercase;
    }
    
    .data-table tbody tr {
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.3s ease;
    }
    
    .data-table tbody tr:hover {
        background: rgba(212, 175, 55, 0.05);
    }
    
    .data-table td {
        padding: 1rem;
        color: #d1d5db;
        font-size: 0.875rem;
    }
    
    .status-active {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid #10b981;
    }
    
    .status-inactive {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid #ef4444;
    }
    
    .btn-gold {
        background: #d4af37;
        color: #0f1419;
        font-weight: 600;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1.5rem;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .btn-gold:hover {
        background: #e6c547;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2);
    }
    
    .btn-small {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .action-link {
        color: #d4af37;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .action-link:hover {
        color: #e6c547;
    }
    
    .action-link-danger {
        color: #ef4444;
        border: none;
        background: none;
        padding: 0;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .action-link-danger:hover {
        color: #f87171;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    }
    
    .page-header h1 {
        color: #fff;
        font-size: 2rem;
        margin: 0;
    }
    
    .page-header-subtext {
        color: #9ca3af;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .header-actions {
        display: flex;
        gap: 1rem;
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid #10b981;
        color: #10b981;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
        font-size: 0.875rem;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen p-4 sm:p-8" style="background: #0f1419;">
    <div class="max-w-7xl mx-auto">

        <!-- PAGE HEADER WITH ACTIONS -->
        <div class="page-header">
            <div>
                <h1>{{ __('messages.members_directory') }}</h1>
                <p class="page-header-subtext">{{ __('messages.members_directory_subtitle') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('members.create') }}" class="btn-gold btn-small">+ {{ __('messages.add_member') }}</a>
            </div>
        </div>

        <!-- SUCCESS MESSAGE (CONDITIONAL) -->
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- MEMBERS TABLE CARD -->
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 12%;">{{ __('messages.card_id') }}</th>
                        <th style="width: 16%;">{{ __('messages.name') }}</th>
                        <th style="width: 16%;">{{ __('messages.email') }}</th>
                        <th style="width: 12%;">{{ __('messages.phone') }}</th>
                        <th style="width: 12%;">{{ __('messages.membership') }}</th>
                        <th style="width: 12%;">{{ __('messages.period') }}</th>
                        <th style="width: 8%; text-align: center;">{{ __('messages.status') }}</th>
                        <th style="width: 12%; text-align: center;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td style="font-weight: 600; color: #d4af37;">{{ $member->card_id ?? '-' }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone }}</td>
                            <td>{{ optional($member->membership)->name ?? '-' }}</td>
                            <td>
                                <span style="display: block; color: #d1d5db;">{{ $member->start_date }}</span>
                                <span style="display: block; color: #6b7280; font-size: 0.75rem;">→ {{ $member->expiry_date?->format('M d, Y') ?? __('messages.not_set') }}</span>
                            </td>
                            <td style="text-align: center;">
                                @if($member->active)
                                    <span class="status-active">{{ __('messages.active') }}</span>
                                @else
                                    <span class="status-inactive">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('members.edit', $member) }}" class="action-link" style="margin-right: 1rem;">{{ __('messages.edit') }}</a>
                                <form action="{{ route('members.destroy', $member) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.confirm_delete_member') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link-danger">{{ __('messages.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                {{ __('messages.no_members') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MEMBERS SUMMARY FOOTER -->
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(212, 175, 55, 0.2); color: #9ca3af; font-size: 0.875rem;">
            <p>{{ __('messages.displaying') }} {{ $members->count() }} {{ __('messages.members') }}</p>
        </div>

    </div>
</div>
@endsection
