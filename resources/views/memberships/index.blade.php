@extends('layouts.app')

@section('content')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { font-family: 'Inter', sans-serif; background: #0f1419; color: #d1d5db; line-height: 1.6; }
    h1, h2, h3, h4, h5, h6 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; text-transform: uppercase; }
    .container { min-height: 100vh; padding: 2rem 1rem; background: #0f1419; }
    @media (min-width: 640px) { .container { padding: 2rem; } }
    .content-wrapper { max-width: 62rem; margin: 0 auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(212, 175, 55, 0.2); }
    .page-header h1 { color: #fff; font-size: 2rem; }
    .btn-gold { background: #d4af37; color: #0f1419; font-weight: 600; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.875rem 1.5rem; border: none; cursor: pointer; font-size: 0.875rem; text-decoration: none; display: inline-block; }
    .btn-gold:hover { background: #e6c547; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2); }
    .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #86efac; padding: 1.25rem; margin-bottom: 2rem; font-size: 0.875rem; }
    .table-wrapper { background: #1a1f2e; border: 1px solid #d4af37; overflow-x: auto; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); }
    table { width: 100%; border-collapse: collapse; }
    thead { background: rgba(212, 175, 55, 0.1); border-bottom: 2px solid #d4af37; }
    th { padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; color: #d4af37; text-transform: uppercase; }
    td { padding: 1rem; border-bottom: 1px solid rgba(212, 175, 55, 0.1); color: #d1d5db; }
    tbody tr:hover { background: rgba(212, 175, 55, 0.05); transition: all 0.3s ease; }
    tbody tr:last-child td { border-bottom: none; }
    .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 0; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .status-active { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .action-links { display: flex; gap: 1rem; }
    .action-link { color: #d4af37; text-decoration: none; font-size: 0.875rem; font-weight: 600; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.05em; }
    .action-link:hover { color: #e6c547; text-decoration: underline; }
    .action-link.delete { color: #ef4444; }
    .action-link.delete:hover { color: #fca5a5; }
    .footer-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(212, 175, 55, 0.2); }
    .stat-item { text-align: center; }
    .stat-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #9ca3af; margin-bottom: 0.5rem; }
    .stat-value { font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: #d4af37; font-weight: 600; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>{{ __('messages.memberships') }}</h1>
            <a href="{{ route('memberships.create') }}" class="btn-gold">+ {{ __('messages.add_membership') }}</a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('messages.membership_type') }}</th>
                        <th>{{ __('messages.annual_price') }}</th>
                        <th>{{ __('messages.duration') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memberships as $membership)
                    <tr>
                        <td><strong>{{ $membership->name }}</strong></td>
                        <td>${{ number_format($membership->price, 2) }}</td>
                        <td>{{ $membership->duration_days }} {{ __('messages.days') }}</td>
                        <td><span class="status-badge status-active">{{ __('messages.active') }}</span></td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('memberships.edit', $membership) }}" class="action-link">{{ __('messages.edit') }}</a>
                                <form action="{{ route('memberships.destroy', $membership) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('{{ __('messages.confirm_delete_membership') }}')" class="action-link delete" style="background: none; border: none; padding: 0; cursor: pointer; font-family: inherit;">{{ __('messages.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #9ca3af; padding: 2rem;"><a href="{{ route('memberships.create') }}" class="action-link">{{ __('messages.create_membership') }}</a> {{ __('messages.create_membership_to_start') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer-stats">
            <div class="stat-item">
                <div class="stat-label">{{ __('messages.total_memberships') }}</div>
                <div class="stat-value">{{ $memberships->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">{{ __('messages.active_status') }}</div>
                <div class="stat-value">{{ $memberships->count() }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
