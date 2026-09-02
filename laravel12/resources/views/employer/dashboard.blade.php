@extends('layouts.app')

@section('title', 'Employer Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .employer-dashboard { --dash-ink: #18232d; --dash-muted: #6d7a80; --dash-line: #dce5e4; --dash-mint: #ccefe3; --dash-green: #087f67; --dash-coral: #f27864; padding: 42px 0 64px; color: var(--dash-ink); }
    .dashboard-hero { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 30px; }
    .dashboard-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: var(--dash-green); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .dashboard-kicker::before { content: ''; width: 22px; height: 2px; background: var(--dash-coral); }
    .dashboard-hero h1 { margin: 0; color: var(--dash-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3.1rem); letter-spacing: -.05em; }
    .dashboard-hero p { margin: 8px 0 0; color: var(--dash-muted); }
    .dashboard-action { display: inline-flex; align-items: center; gap: 9px; flex-shrink: 0; padding: 13px 18px; border-radius: 10px; background: var(--dash-green); color: #fff; font-weight: 700; text-decoration: none; transition: background .2s; }
    .dashboard-action:hover { background: #05634f; color: #fff; text-decoration: none; }
    .dashboard-action i { font-size: .8rem; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 34px; }
    .metric-card { position: relative; min-height: 138px; padding: 22px; overflow: hidden; border: 1px solid var(--dash-line); border-radius: 12px; background: #fff; }
    .metric-card::after { position: absolute; right: 20px; bottom: 15px; color: var(--dash-mint); font: 900 3.6rem 'Font Awesome 6 Free'; }
    .metric-card:nth-child(1)::after { content: '\f1b2'; }
    .metric-card:nth-child(2)::after { content: '\f058'; color: #d8f1e5; }
    .metric-card:nth-child(3)::after { content: '\f017'; color: #ffe5df; }
    .metric-card:nth-child(4)::after { content: '\f0c0'; color: #dfeeea; }
    .metric-label { position: relative; z-index: 1; color: var(--dash-muted); font-size: .78rem; font-weight: 700; }
    .metric-value { position: relative; z-index: 1; display: block; margin-top: 12px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 2.45rem; line-height: 1; letter-spacing: -.05em; }
    .jobs-section { border: 1px solid var(--dash-line); border-radius: 12px; background: #fff; overflow: hidden; }
    .section-heading { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 22px 24px; border-bottom: 1px solid var(--dash-line); }
    .section-heading h2 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; letter-spacing: -.03em; }
    .section-tools { display: flex; align-items: center; gap: 12px; }
    .section-tools a { color: var(--dash-green); font-size: .82rem; font-weight: 700; text-decoration: none; }
    .section-tools a:hover { text-decoration: underline; }
    .dashboard-toolbar { display: flex; align-items: center; gap: 16px; padding: 18px 24px; border-bottom: 1px solid var(--dash-line); }
    .dashboard-search { display: flex; width: min(100%, 420px); }
    .dashboard-search input { min-width: 0; flex: 1; border: 1px solid var(--dash-line); border-right: 0; border-radius: 9px 0 0 9px; padding: 11px 13px; color: var(--dash-ink); background: #fff; outline: 0; font: inherit; font-size: .82rem; }
    .dashboard-search input:focus { border-color: var(--dash-green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .dashboard-search button { border: 1px solid var(--dash-green); border-radius: 0 9px 9px 0; padding: 0 15px; color: #fff; background: var(--dash-green); cursor: pointer; font: 700 .78rem 'DM Sans', sans-serif; }
    .dashboard-search button:hover { background: #05634f; }
    .clear-search { color: var(--dash-muted); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .clear-search:hover { color: var(--dash-green); }
    .jobs-table-wrap { overflow-x: auto; }
    .jobs-table { min-width: 700px; margin: 0; vertical-align: middle; }
    .jobs-table thead th { padding: 14px 24px; border: 0; color: var(--dash-muted); background: #f7faf8; font-size: .7rem; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap; }
    .jobs-table tbody td { padding: 18px 24px; border-color: #edf2f0; color: var(--dash-muted); font-size: .86rem; }
    .jobs-table tbody tr:last-child td { border-bottom: 0; }
    .job-title { color: var(--dash-ink); font-weight: 700; }
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700; }
    .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-approved { color: #087f67; background: #e6f7f0; }
    .status-pending { color: #ad7111; background: #fff5d9; }
    .status-rejected { color: #c64c3d; background: #fff0ed; }
    .view-applications { color: var(--dash-green); font-size: .8rem; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .view-applications i { margin-left: 5px; font-size: .7rem; transition: transform .2s; }
    .view-applications:hover { color: #05634f; }
    .view-applications:hover i { transform: translateX(4px); }
    .dashboard-pagination { display: flex; align-items: center; justify-content: flex-end; gap: 16px; padding: 16px 22px; border-top: 1px solid var(--dash-line); }
    .per-page-form { display: flex; align-items: center; gap: 9px; color: var(--dash-muted); font-size: .78rem; white-space: nowrap; }
    .per-page-form select { border: 1px solid var(--dash-line); border-radius: 7px; padding: 7px 28px 7px 9px; color: var(--dash-ink); background: #fff; font: inherit; outline: 0; cursor: pointer; }
    .per-page-form select:focus { border-color: var(--dash-green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .pagination-info { color: var(--dash-muted); font-size: .78rem; }
    .pagination-nav { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
    .pagination-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px; border: 1px solid var(--dash-line); border-radius: 7px; color: var(--dash-green); background: #fff; font-size: .78rem; font-weight: 700; text-decoration: none; }
    .pagination-link:hover { border-color: var(--dash-green); color: var(--dash-green); background: #eaf8f2; text-decoration: none; }
    .pagination-link.is-current { border-color: var(--dash-green); color: #fff; background: var(--dash-green); }
    .pagination-link.is-disabled { color: #9aa8aa; background: #f7faf8; cursor: not-allowed; pointer-events: none; }
    .empty-state { padding: 58px 24px; text-align: center; }
    .empty-icon { display: grid; place-items: center; width: 52px; height: 52px; margin: 0 auto 14px; border-radius: 14px; color: var(--dash-green); background: var(--dash-mint); }
    .empty-state h3 { margin: 0 0 6px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.15rem; }
    .empty-state p { margin: 0 0 20px; color: var(--dash-muted); font-size: .88rem; }
    @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    .jobs-section.is-loading #dashboard-results { opacity: .5; pointer-events: none; transition: opacity .2s; }
    @media (max-width: 620px) { .employer-dashboard { padding: 28px 0 44px; } .dashboard-hero { align-items: flex-start; flex-direction: column; } .dashboard-action { width: 100%; justify-content: center; } .stats-grid { gap: 10px; } .metric-card { min-height: 118px; padding: 17px; } .metric-value { font-size: 2rem; } .section-heading { padding: 18px; } .section-tools { display: none; } .dashboard-toolbar { align-items: stretch; flex-direction: column; gap: 10px; padding: 18px; } .dashboard-search { width: 100%; } .dashboard-pagination { flex-direction: column; align-items: flex-start; padding: 16px 18px; } .jobs-table thead th, .jobs-table tbody td { padding-right: 18px; padding-left: 18px; } }
</style>
@endpush

@section('content')
<div class="container employer-dashboard">
    <div class="dashboard-hero">
        <div>
            <span class="dashboard-kicker">Employer workspace</span>
            <h1>Good morning, {{ auth()->user()->name }}.</h1>
            <p>Keep your hiring pipeline moving with a quick look at your jobs.</p>
        </div>
        <a href="{{ route('employer.jobs.create') }}" class="dashboard-action"><i class="bi bi-plus-lg" aria-hidden="true"></i> Post a new job</a>
    </div>

    <div class="stats-grid">
        <div class="metric-card"><span class="metric-label">Posted jobs</span><strong class="metric-value">{{ $stats['total_jobs'] ?? 0 }}</strong></div>
        <div class="metric-card"><span class="metric-label">Active jobs</span><strong class="metric-value">{{ $stats['active_jobs'] ?? 0 }}</strong></div>
        <div class="metric-card"><span class="metric-label">Pending approval</span><strong class="metric-value">{{ $stats['pending_jobs'] ?? 0 }}</strong></div>
        <div class="metric-card"><span class="metric-label">Applications</span><strong class="metric-value">{{ $stats['total_applications'] ?? 0 }}</strong></div>
    </div>

    <section class="jobs-section" aria-labelledby="recent-jobs-heading">
        <div class="section-heading">
            <h2 id="recent-jobs-heading">Recent jobs</h2>
            <div class="section-tools">
                <a href="{{ route('employer.jobs') }}">View all jobs <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>

        <div class="dashboard-toolbar">
            <form class="dashboard-search" method="GET" action="{{ route('employer.dashboard') }}">
                <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search jobs by title, keyword or location" aria-label="Search jobs">
                <button type="submit"><i class="bi bi-search" aria-hidden="true"></i> Search</button>
            </form>
            @if(!empty($search))<a class="clear-search" href="{{ route('employer.dashboard') }}">Clear</a>@endif
        </div>

        <div id="dashboard-results">
        @if(isset($jobs) && $jobs->count() > 0)
            <div class="jobs-table-wrap">
                <table class="table jobs-table">
                    <thead><tr><th>Job title</th><th>Status</th><th>Applications</th><th>Posted</th><th>Action</th></tr></thead>
                    <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td class="job-title">{{ $job->title }}</td>
                            <td><span class="status-pill status-{{ $job->status == 'approved' ? 'approved' : ($job->status == 'pending' ? 'pending' : 'rejected') }}">{{ ucfirst($job->status) }}</span></td>
                            <td>{{ $job->applications_count }}</td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                            <td><a class="view-applications" href="{{ route('employer.jobs.applications', $job->id) }}">View applications <i class="bi bi-arrow-right" aria-hidden="true"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if($jobs->hasPages())
                <div class="dashboard-pagination">
                    <form class="per-page-form" method="GET" action="{{ route('employer.dashboard') }}">
                        @if(!empty($search))<input type="hidden" name="search" value="{{ $search }}">@endif
                        <label for="dashboard_per_page">Show</label>
                        <select id="dashboard_per_page" name="per_page" aria-label="Jobs per page" onchange="this.form.submit()">
                            @foreach([5, 6, 10, 20, 50] as $option)
                                <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        <span>per page</span>
                    </form>
                    <span class="pagination-info">Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }}</span>
                    <nav class="pagination-nav" aria-label="Jobs pagination">
                        <a class="pagination-link {{ $jobs->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $jobs->previousPageUrl() ?? '#' }}" aria-label="Previous page">Previous</a>
                        @for($page = 1; $page <= $jobs->lastPage(); $page++)
                            <a class="pagination-link {{ $jobs->currentPage() === $page ? 'is-current' : '' }}" href="{{ $jobs->url($page) }}" aria-current="{{ $jobs->currentPage() === $page ? 'page' : 'false' }}">{{ $page }}</a>
                        @endfor
                        <a class="pagination-link {{ $jobs->currentPage() === $jobs->lastPage() ? 'is-disabled' : '' }}" href="{{ $jobs->nextPageUrl() ?? '#' }}" aria-label="Next page">Next</a>
                    </nav>
                </div>
            @endif
        @else
            <div class="empty-state"><div class="empty-icon"><i class="bi bi-briefcase" aria-hidden="true"></i></div><h3>Your hiring board is ready</h3><p>Post your first role and start meeting great candidates.</p><a href="{{ route('employer.jobs.create') }}" class="dashboard-action"><i class="bi bi-plus-lg" aria-hidden="true"></i> Post a new job</a></div>
        @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const section = document.querySelector('.jobs-section');
    const results = document.querySelector('#dashboard-results');

    const loadDashboard = async (url, pushState = true) => {
        if (!results || !section) return;
        section.classList.add('is-loading');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
            });

            if (!response.ok) throw new Error('Unable to load dashboard jobs');

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const incomingResults = doc.querySelector('#dashboard-results');
            if (!incomingResults) throw new Error('Unable to load dashboard results');
            results.innerHTML = incomingResults.innerHTML;

            if (pushState) history.pushState({}, '', url);
        } catch (error) {
            window.location.href = url;
        } finally {
            section.classList.remove('is-loading');
        }
    };

    section.addEventListener('submit', (event) => {
        const form = event.target.closest('.dashboard-search');
        if (!form) return;
        event.preventDefault();
        const url = new URL(form.action, window.location.origin);
        const search = form.querySelector('[name="search"]').value.trim();
        const perPage = results.querySelector('#dashboard_per_page')?.value;
        if (search) url.searchParams.set('search', search);
        if (perPage) url.searchParams.set('per_page', perPage);
        loadDashboard(url.toString());
    });

    section.addEventListener('change', (event) => {
        const select = event.target.closest('#dashboard_per_page');
        if (!select) return;
        const url = new URL(window.location.href);
        const search = results.querySelector('.dashboard-search [name="search"]')?.value.trim();
        if (search) url.searchParams.set('search', search);
        url.searchParams.set('per_page', select.value);
        url.searchParams.delete('page');
        loadDashboard(url.toString());
    });

    section.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination-link');
        if (!link || link.classList.contains('is-disabled')) return;
        event.preventDefault();
        loadDashboard(link.href);
    });

    window.addEventListener('popstate', () => loadDashboard(window.location.href, false));
})();
</script>
@endpush
