@extends('layouts.app')

@section('content')
<style>
    * { font-family: 'Inter', sans-serif; }
    h1, h2, h3, h4 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; }
    
    body {
        background: #0f1419;
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
    
    .role-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .role-admin {
        background: rgba(212, 175, 55, 0.15);
        color: #d4af37;
        border: 1px solid #d4af37;
    }
    
    .role-staff {
        background: rgba(59, 130, 246, 0.15);
        color: #3b82f6;
        border: 1px solid #3b82f6;
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
        text-decoration: none;
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
    
    .alert-error {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid #ef4444;
        color: #ef4444;
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
    
    .filter-bar {
        background: #1a1f2e;
        border: 1px solid #d4af37;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-input {
        background: #0f1419;
        border: 1px solid #d4af37;
        color: #e0e0e0;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #e6c547;
    }
    
    .filter-select {
        background: #0f1419;
        border: 1px solid #d4af37;
        color: #e0e0e0;
        padding: 0.5rem 1rem;
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
                <h1>{{ __('messages.user_management') }}</h1>
                <p class="page-header-subtext">{{ __('messages.user_management_subtitle') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('users.create') }}" class="btn-gold btn-small">+ {{ __('messages.add_user') }}</a>
            </div>
        </div>

        <!-- SUCCESS/ERROR MESSAGE -->
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- FILTER BAR -->
        <form method="GET" action="{{ route('users.index') }}" class="filter-bar">
            <input type="text" name="search" class="filter-input" placeholder="{{ __('messages.search_users') }}" value="{{ request('search') }}" style="flex: 1; min-width: 200px;">
            
            <select name="role" class="filter-select">
                <option value="">{{ __('messages.all_roles') }}</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>{{ __('messages.staff') }}</option>
            </select>
            
            <select name="status" class="filter-select">
                <option value="">{{ __('messages.all_status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
            </select>
            
            <button type="submit" class="btn-gold btn-small">{{ __('messages.filter') }}</button>
        </form>

        <!-- USERS TABLE -->
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 20%;">{{ __('messages.name') }}</th>
                        <th style="width: 25%;">{{ __('messages.email') }}</th>
                        <th style="width: 12%;">{{ __('messages.role') }}</th>
                        <th style="width: 12%; text-align: center;">{{ __('messages.status') }}</th>
                        <th style="width: 14%;">{{ __('messages.created_at') }}</th>
                        <th style="width: 12%; text-align: center;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td style="color: #6b7280;">{{ $user->id }}</td>
                            <td style="font-weight: 600; color: #fff;">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span style="color: #d4af37; font-size: 0.75rem;">({{ __('messages.you') }})</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-staff' }}">
                                    {{ $user->role === 'admin' ? __('messages.admin') : __('messages.staff') }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @if($user->active)
                                    <span class="status-active">{{ __('messages.active') }}</span>
                                @else
                                    <span class="status-inactive">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td style="color: #6b7280;">{{ $user->created_at->format('M d, Y') }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('users.edit', $user) }}" class="action-link" style="margin-right: 0.75rem;">{{ __('messages.edit') }}</a>
                                
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.toggle-status', $user) }}" method="POST" style="display: inline; margin-right: 0.75rem;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-link" style="background: none; border: none; padding: 0;">
                                            {{ $user->active ? __('messages.deactivate') : __('messages.activate') }}
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('messages.confirm_delete_user') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link-danger">{{ __('messages.delete') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                {{ __('messages.no_users') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($users->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $users->links() }}
            </div>
        @endif

        <!-- SUMMARY FOOTER -->
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(212, 175, 55, 0.2); color: #9ca3af; font-size: 0.875rem;">
            <p>{{ __('messages.displaying') }} {{ $users->count() }} {{ __('messages.of') }} {{ $users->total() }} {{ __('messages.users') }}</p>
        </div>

    </div>
</div>
@endsection
