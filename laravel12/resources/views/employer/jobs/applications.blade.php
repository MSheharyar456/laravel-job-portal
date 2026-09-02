@extends('layouts.app')

@section('title', 'Job Applications')

@push('styles')
<style>
    .applications-page { --apps-ink: #18232d; --apps-muted: #6d7a80; --apps-line: #dce5e4; --apps-mint: #ccefe3; --apps-green: #087f67; --apps-coral: #f27864; padding: 42px 0 64px; color: var(--apps-ink); }
    .applications-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 28px; }
    .applications-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: var(--apps-green); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .applications-kicker::before { content: ''; width: 22px; height: 2px; background: var(--apps-coral); }
    .applications-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .applications-header p { margin: 8px 0 0; color: var(--apps-muted); }
    .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--apps-muted); font-size: .82rem; font-weight: 700; text-decoration: none; }
    .back-link:hover { color: var(--apps-green); }
    .applications-card { border: 1px solid var(--apps-line); border-radius: 12px; background: #fff; overflow: hidden; }
    .applications-summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 24px; background: #f7faf8; border-bottom: 1px solid var(--apps-line); }
    .applications-summary strong { color: var(--apps-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.1rem; }
    .applications-summary span { color: var(--apps-muted); font-size: .78rem; font-weight: 700; }
    .applications-table-wrap { overflow-x: auto; }
    .applications-table { min-width: 980px; margin: 0; vertical-align: middle; }
    .applications-table thead th { padding: 14px 22px; border: 0; background: #f7faf8; color: var(--apps-muted); font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; }
    .applications-table tbody td { padding: 18px 22px; border-color: #edf2f0; color: var(--apps-muted); font-size: .84rem; }
    .candidate-name { display: block; color: var(--apps-ink); font-weight: 700; }
    .candidate-meta { display: block; margin-top: 4px; color: var(--apps-muted); font-size: .72rem; }
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700; }
    .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-pending { color: #ad7111; background: #fff5d9; }
    .status-reviewed { color: #325a8c; background: #eaf2ff; }
    .status-shortlisted { color: #087f67; background: #e6f7f0; }
    .status-accepted { color: #087f67; background: #e6f7f0; }
    .status-rejected { color: #c64c3d; background: #fff0ed; }
    .resume-link { color: var(--apps-green); font-weight: 700; text-decoration: none; }
    .resume-link:hover { text-decoration: underline; }
    .status-form { display: inline-flex; align-items: center; }
    .status-select { border: 1px solid var(--apps-line); border-radius: 8px; padding: 8px 12px; color: var(--apps-ink); background: #fff; font: inherit; font-size: .8rem; outline: 0; }
    .status-select:focus { border-color: var(--apps-green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .text-quiet { color: var(--apps-muted); }
    .applications-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 14px; }
    .applications-search { display: flex; width: min(100%, 390px); margin-left: auto; }
    .applications-search input { min-width: 0; flex: 1; border: 1px solid var(--apps-line); border-right: 0; border-radius: 9px 0 0 9px; padding: 11px 13px; color: var(--apps-ink); background: #fff; outline: 0; font: inherit; font-size: .82rem; }
    .applications-search input:focus { border-color: var(--apps-green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .applications-search button { display: inline-flex; align-items: center; gap: 7px; border: 1px solid var(--apps-green); border-radius: 0 9px 9px 0; padding: 0 15px; color: #fff; background: var(--apps-green); cursor: pointer; font: 700 .78rem 'DM Sans', sans-serif; }
    .applications-search button:hover { background: #05634f; }
    .applications-clear { color: var(--apps-muted); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .applications-clear:hover { color: var(--apps-green); }
    .applications-pagination { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 22px; border-top: 1px solid var(--apps-line); }
    .applications-per-page { display: flex; align-items: center; gap: 9px; color: var(--apps-muted); font-size: .78rem; white-space: nowrap; }
    .applications-per-page select { border: 1px solid var(--apps-line); border-radius: 7px; padding: 7px 28px 7px 9px; color: var(--apps-ink); background: #fff; font: inherit; outline: 0; cursor: pointer; }
    .applications-pagination-info { color: var(--apps-muted); font-size: .78rem; }
    .applications-pagination-nav { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
    .applications-pagination-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px; border: 1px solid var(--apps-line); border-radius: 7px; color: var(--apps-green); background: #fff; font-size: .78rem; font-weight: 700; text-decoration: none; }
    .applications-pagination-link.is-current { border-color: var(--apps-green); color: #fff; background: var(--apps-green); }
    .applications-pagination-link.is-disabled { color: #aebbbb; background: #f7faf8; pointer-events: none; }
    .applications-results.is-loading { opacity: .5; pointer-events: none; transition: opacity .2s; }
    .empty-state { padding: 70px 24px; text-align: center; }
    .empty-icon { display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 15px; border-radius: 14px; color: var(--apps-green); background: var(--apps-mint); }
    .empty-state h2 { margin: 0 0 7px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; }
    .empty-state p { margin: 0; color: var(--apps-muted); font-size: .88rem; }
    @media (max-width: 620px) { .applications-page { padding: 28px 0 44px; } .applications-header { align-items: flex-start; flex-direction: column; gap: 14px; } .applications-toolbar { align-items: stretch; flex-direction: column; gap: 10px; } .applications-search { width: 100%; } .applications-summary { flex-direction: column; align-items: flex-start; } .applications-pagination { align-items: flex-start; flex-direction: column; padding-right: 17px; padding-left: 17px; } }
</style>
@endpush

@section('content')
<div class="container applications-page">
    <div class="applications-header">
        <div>
            <span class="applications-kicker">Employer workspace</span>
            <h1>Applications for {{ $job->title }}</h1>
            <p>Review candidates, track decisions, and keep your hiring process moving.</p>
        </div>
        <a class="back-link" href="{{ route('employer.jobs') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to jobs</a>
    </div>

    <div class="applications-toolbar">
        <div class="applications-summary"><strong data-applications-summary>{{ $applications->total() }}</strong><span>{{ $applications->total() === 1 ? 'candidate application' : 'candidate applications' }}</span></div>
        <form class="applications-search" method="GET" action="{{ url()->current() }}">
            <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by candidate, email or status" aria-label="Search applications">
            <button type="submit"><i class="bi bi-search" aria-hidden="true"></i> Search</button>
        </form>
        <a class="applications-clear" href="{{ url()->current() }}" style="{{ empty($search) ? 'display: none;' : '' }}">Clear search</a>
    </div>

    <div id="applications-results" class="applications-results">@include('employer.jobs._applications_results')</div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const results = document.getElementById('applications-results');
    const searchForm = document.querySelector('.applications-search');
    const summary = document.querySelector('[data-applications-summary]');

    const loadApplications = async (url, pushState = true) => {
        results.classList.add('is-loading');
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            if (!response.ok) throw new Error('Unable to load applications');
            results.innerHTML = await response.text();
            const search = url.searchParams.get('search')?.trim() ?? '';
            summary.textContent = results.querySelector('[data-applications-total]')?.dataset.applicationsTotal ?? '0';
            document.querySelector('.applications-clear').style.display = search ? '' : 'none';
            if (pushState) history.pushState({}, '', url);
        } catch (error) {
            window.location.href = url;
        } finally { results.classList.remove('is-loading'); }
    };

    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = new URL(searchForm.action, window.location.origin);
        const search = searchForm.querySelector('[name="search"]').value.trim();
        if (search) url.searchParams.set('search', search);
        url.searchParams.set('per_page', results.querySelector('#applications_per_page')?.value ?? '10');
        url.searchParams.delete('page');
        loadApplications(url.toString());
    });

    results.addEventListener('change', (event) => {
        if (event.target.id !== 'applications_per_page') return;
        const url = new URL(window.location.href);
        if (searchForm.querySelector('[name="search"]').value.trim()) url.searchParams.set('search', searchForm.querySelector('[name="search"]').value.trim());
        url.searchParams.set('per_page', event.target.value);
        url.searchParams.delete('page');
        loadApplications(url.toString());
    });

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('.status-form');
        if (!form) return;
        if (form.dataset.submitting === 'true') {
            event.preventDefault();
            return;
        }
        form.dataset.submitting = 'true';
    });

    document.addEventListener('click', (event) => {
        const clearLink = event.target.closest('.applications-clear');
        if (clearLink) {
            event.preventDefault();
            searchForm.querySelector('[name="search"]').value = '';
            loadApplications(clearLink.href);
            return;
        }
    });

    results.addEventListener('click', (event) => {
        const link = event.target.closest('.applications-pagination-link');
        if (!link || link.classList.contains('is-disabled')) return;
        event.preventDefault();
        loadApplications(link.href);
    });

    window.addEventListener('popstate', () => loadApplications(window.location.href, false));
})();
</script>
@endpush