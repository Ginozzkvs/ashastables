@extends('layouts.app')

@section('content')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { font-family: 'Inter', sans-serif; background: #0f1419; color: #d1d5db; line-height: 1.6; }
    h1, h2, h3, h4, h5, h6 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; text-transform: uppercase; }
    .container { min-height: 100vh; padding: 2rem 1rem; background: #0f1419; }
    @media (min-width: 640px) { .container { padding: 2rem; } }
    .content-wrapper { max-width: 48rem; margin: 0 auto; }
    .page-header { margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(212, 175, 55, 0.2); }
    .page-header h1 { color: #fff; font-size: 2rem; margin-bottom: 0.5rem; }
    .page-header-subtext { color: #9ca3af; font-size: 0.875rem; font-weight: 300; }
    .info-card { background: rgba(212, 175, 55, 0.05); border: 1px solid rgba(212, 175, 55, 0.2); padding: 1.5rem; margin-bottom: 2rem; }
    .info-row { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.1); }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #9ca3af; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }
    .info-value { color: #d4af37; font-weight: 600; }
    .card-base { background: #1a1f2e; border: 1px solid #d4af37; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); transition: all 0.3s ease; }
    .card-base:hover { box-shadow: 0 8px 20px rgba(212, 175, 55, 0.1); }
    .form-group { margin-bottom: 2rem; }
    .form-label { display: block; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; color: #d4af37; text-transform: uppercase; margin-bottom: 0.75rem; }
    .form-input, .form-select { width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid #d4af37; color: #fff; padding: 0.875rem 1rem; font-size: 0.875rem; transition: all 0.3s ease; font-family: 'Inter', sans-serif; }
    .form-input:focus, .form-select:focus { outline: none; background: rgba(212, 175, 55, 0.1); border-color: #e6c547; box-shadow: 0 0 12px rgba(212, 175, 55, 0.15); }
    .form-input::placeholder { color: #6b7280; }
    .form-select option { background: #1a1f2e; color: #fff; }
    .help-text { color: #6b7280; font-size: 0.75rem; margin-top: 0.5rem; }
    .btn-gold { background: #d4af37; color: #0f1419; font-weight: 600; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.875rem 2rem; border: none; cursor: pointer; font-size: 0.875rem; }
    .btn-gold:hover { background: #e6c547; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2); }
    .btn-outline { border: 1px solid #d4af37; color: #d4af37; background: transparent; font-weight: 600; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.875rem 2rem; cursor: pointer; font-size: 0.875rem; text-decoration: none; display: inline-block; }
    .btn-outline:hover { background: rgba(212, 175, 55, 0.1); transform: translateY(-2px); }
    .btn-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid #ef4444; font-weight: 600; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.875rem 2rem; cursor: pointer; font-size: 0.875rem; }
    .btn-danger:hover { background: rgba(239, 68, 68, 0.25); transform: translateY(-2px); }
    .form-actions { display: flex; gap: 1rem; margin-top: 2rem; justify-content: space-between; }
    .form-actions-right { display: flex; gap: 1rem; }
    .danger-zone { background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); padding: 2rem; margin-top: 3rem; }
    .danger-zone h3 { color: #ef4444; font-size: 1rem; margin-bottom: 0.5rem; }
    .danger-zone p { color: #9ca3af; font-size: 0.875rem; margin-bottom: 1rem; }
    .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #fecaca; padding: 1.25rem; margin-bottom: 2rem; font-size: 0.875rem; }
    .alert-error ul { list-style: none; padding: 0; }
    .alert-error li { margin: 0.5rem 0; }
    .activities-link { margin-top: 2rem; padding: 1.5rem; background: rgba(212, 175, 55, 0.05); border: 1px solid rgba(212, 175, 55, 0.3); }
    .activities-link h3 { color: #d4af37; font-size: 1rem; margin-bottom: 0.5rem; font-family: 'Cormorant Garamond', serif; }
    .activities-link p { color: #9ca3af; font-size: 0.875rem; margin-bottom: 1rem; }
    .activities-link a { color: #d4af37; text-decoration: none; font-weight: 600; }
    .activities-link a:hover { color: #e6c547; text-decoration: underline; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>Edit Membership</h1>
            <p class="page-header-subtext">Update membership tier details and pricing</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-card">
            <div class="info-row">
                <span class="info-label">Membership ID</span>
                <span class="info-value">#{{ $membership->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Created Date</span>
                <span class="info-value">{{ $membership->created_at->format('M d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Last Updated</span>
                <span class="info-value">{{ $membership->updated_at->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="card-base">
            <form method="POST" action="{{ route('memberships.update', $membership->id) }}" novalidate>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Membership Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $membership->name) }}" required>
                    <p class="help-text">Unique name for this membership tier</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Annual Price</label>
                    <input type="number" name="price" class="form-input" value="{{ old('price', $membership->price) }}" step="0.01" min="0" required>
                    <p class="help-text">Annual membership fee in USD</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Duration (Days)</label>
                    <input type="number" name="duration_days" class="form-input" value="{{ old('duration_days', $membership->duration_days) }}" min="1" required>
                    <p class="help-text">How long the membership is valid (365 = 1 year)</p>
                </div>

                <div class="activities-link">
                    <h3>Activity Limits</h3>
                    <p>Configure which activities are included in this membership and set usage limits.</p>
                    <a href="{{ route('membership-activity-limits.index') }}">Manage Activity Limits →</a>
                </div>

                <div class="form-actions">
                    <a href="{{ route('memberships.index') }}" class="btn-outline">Cancel</a>
                    <div class="form-actions-right">
                        <button type="submit" class="btn-gold">Update Membership</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="danger-zone">
            <h3>Delete This Membership</h3>
            <p>Once you delete a membership tier, there is no going back. Members assigned to this tier will need to be reassigned.</p>
            <form action="{{ route('memberships.destroy', $membership) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Are you sure you want to delete this membership?')" class="btn-danger">Delete Membership</button>
            </form>
        </div>
    </div>
</div>

@endsection
