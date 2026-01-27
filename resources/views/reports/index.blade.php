@extends('layouts.app')

@section('content')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { font-family: 'Inter', sans-serif; background: #0f1419; color: #d1d5db; line-height: 1.6; }
    h1, h2, h3 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; text-transform: uppercase; }
    .container { min-height: 100vh; padding: 2rem 1rem; background: #0f1419; }
    @media (min-width: 640px) { .container { padding: 2rem; } }
    .content-wrapper { max-width: 88rem; margin: 0 auto; }
    
    .page-header { margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(212, 175, 55, 0.2); }
    .page-header h1 { color: #fff; font-size: 2.5rem; margin-bottom: 0.5rem; }
    
    .reports-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; }
    .report-card { background: #1a1f2e; border: 1px solid #d4af37; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); transition: all 0.3s ease; cursor: pointer; }
    .report-card:hover { box-shadow: 0 8px 20px rgba(212, 175, 55, 0.15); transform: translateY(-4px); }
    
    .report-icon { font-size: 2.5rem; margin-bottom: 1rem; }
    .report-title { color: #d4af37; font-size: 1.5rem; margin-bottom: 0.5rem; font-family: 'Cormorant Garamond', serif; }
    .report-description { color: #9ca3af; font-size: 0.875rem; line-height: 1.6; margin-bottom: 1.5rem; }
    
    .report-actions { display: flex; gap: 0.75rem; }
    .btn-sm { padding: 0.5rem 1rem; border: 1px solid #d4af37; color: #d4af37; background: transparent; text-decoration: none; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; transition: all 0.3s; cursor: pointer; }
    .btn-sm:hover { background: rgba(212, 175, 55, 0.1); }
    .btn-sm.primary { background: #d4af37; color: #0f1419; }
    .btn-sm.primary:hover { background: #e6c547; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>{{ __('messages.reports_exports') }}</h1>
            <p style="color: #9ca3af; font-size: 0.875rem;">{{ __('messages.reports_subtitle') }}</p>
        </div>

        <div class="reports-grid">
            <!-- Revenue Report -->
            <div class="report-card">
                <div class="report-title">{{ __('messages.revenue_report') }}</div>
                <div class="report-description">
                    {{ __('messages.revenue_report_desc') }}
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.revenue') }}" class="btn-sm primary">{{ __('messages.view_report') }}</a>
                    <a href="{{ route('reports.export.revenue') }}" class="btn-sm">{{ __('messages.export_csv') }}</a>
                </div>
            </div>

            <!-- Member Analytics -->
            <div class="report-card">
                <div class="report-title">{{ __('messages.member_analytics') }}</div>
                <div class="report-description">
                    {{ __('messages.member_analytics_desc') }}
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.members') }}" class="btn-sm primary">{{ __('messages.view_report') }}</a>
                    <a href="{{ route('reports.export.members') }}" class="btn-sm">{{ __('messages.export_csv') }}</a>
                </div>
            </div>

            <!-- Activity Usage -->
            <div class="report-card">
                <div class="report-title">{{ __('messages.activity_usage') }}</div>
                <div class="report-description">
                    {{ __('messages.activity_usage_desc') }}
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.activities') }}" class="btn-sm primary">{{ __('messages.view_report') }}</a>
                    <a href="{{ route('reports.export.activities') }}" class="btn-sm">{{ __('messages.export_csv') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
