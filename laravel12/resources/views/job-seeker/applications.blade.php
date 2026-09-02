@extends('layouts.app')

@section('title', 'My Applications')

@push('styles')
<style>
    .applications-page { --applications-ink: #18232d; --applications-muted: #6d7a80; --applications-line: #dce5e4; --applications-teal: #087f67; --applications-mint: #ccefe3; max-width: 1120px; padding: 18px 0 54px; color: var(--applications-ink); }
    .applications-header { margin-bottom: 27px; }
    .applications-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 9px; color: var(--applications-teal); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .applications-kicker::before { width: 22px; height: 2px; background: #f27864; content: ''; }
    .applications-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .applications-header p { margin: 8px 0 0; color: var(--applications-muted); }
    .applications-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 15px; }
    .applications-search { display: flex; width: min(100%, 410px); }
    .applications-search input { min-width: 0; flex: 1; border: 1px solid var(--applications-line); border-right: 0; border-radius: 9px 0 0 9px; padding: 11px 13px; color: var(--applications-ink); outline: 0; font: inherit; font-size: .82rem; }
    .applications-search input:focus { border-color: var(--applications-teal); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .applications-search button { border: 1px solid var(--applications-teal); border-radius: 0 9px 9px 0; padding: 0 15px; color: #fff; background: var(--applications-teal); cursor: pointer; font: 700 .78rem 'DM Sans', sans-serif; }
    .applications-clear { color: var(--applications-muted); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .applications-summary { color: var(--applications-muted); font-size: .82rem; }
    .applications-summary strong { color: var(--applications-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .applications-summary { margin-bottom: 14px; color: var(--applications-muted); font-size: .82rem; }
    .applications-summary strong { color: var(--applications-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .applications-card { overflow: hidden; border: 1px solid var(--applications-line); border-radius: 12px; background: #fff; box-shadow: none; }
    .applications-card .table-responsive { overflow-x: auto; }
    .applications-table { min-width: 780px; margin: 0; vertical-align: middle; }
    .applications-table thead th { padding: 14px 22px; border: 0; background: #f7faf8; color: var(--applications-muted); font-size: .69rem; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap; }
    .applications-table tbody td { padding: 19px 22px; border-color: #edf2f0; color: var(--applications-muted); font-size: .84rem; }
    .applications-table tbody tr:last-child td { border-bottom: 0; }
    .application-job { color: var(--applications-teal); font-weight: 700; text-decoration: none; }
    .application-job:hover { color: #05634f; text-decoration: underline; }
    .application-company { display: block; color: var(--applications-ink); font-weight: 600; }
    .application-location { display: block; margin-top: 4px; color: var(--applications-muted); font-size: .73rem; }
    .application-location i { margin-right: 4px; color: var(--applications-teal); }
    .application-status { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700; }
    .application-status::before { width: 6px; height: 6px; border-radius: 50%; background: currentColor; content: ''; }
    .application-pending { color: #ad7111; background: #fff5d9; }
    .application-accepted { color: #087f67; background: #e6f7f0; }
    .application-rejected { color: #c64c3d; background: #fff0ed; }
    .application-action { display: inline-flex; align-items: center; gap: 7px; border-color: var(--applications-teal); border-radius: 8px; color: var(--applications-teal); font-size: .76rem; font-weight: 700; white-space: nowrap; }
    .application-action:hover { border-color: var(--applications-teal); color: #fff; background: var(--applications-teal); transform: none; }
    .pagination-area { padding: 16px 22px; border-top: 1px solid var(--applications-line); }
    .pagination-area .pagination { gap: 5px; margin: 0; }
    .pagination-area .page-link { border: 1px solid var(--applications-line); border-radius: 7px; color: var(--applications-teal); font-size: .8rem; }
    .pagination-area .page-item.active .page-link { border-color: var(--applications-teal); color: #fff; background: var(--applications-teal); }
    .pagination-area .page-item.disabled .page-link { color: #aebbbb; background: #f7faf8; }
    .pagination-area .page-link { transition: none; }
    .per-page-form { display: flex; align-items: center; gap: 9px; color: var(--applications-muted); font-size: .78rem; white-space: nowrap; }
    .per-page-form select { border: 1px solid var(--applications-line); border-radius: 7px; padding: 7px 28px 7px 9px; color: var(--applications-ink); background: #fff; font: inherit; outline: 0; cursor: pointer; }
    .pagination-area { display: flex; align-items: center; justify-content: space-between; gap: 18px; }
    .pagination-nav { display: flex; align-items: center; gap: 5px; }
    .pagination-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px; border: 1px solid var(--applications-line); border-radius: 7px; color: var(--applications-teal); background: #fff; font-size: .78rem; font-weight: 700; text-decoration: none; }
    .pagination-link.is-current { border-color: var(--applications-teal); color: #fff; background: var(--applications-teal); }
    .pagination-link.is-disabled { color: #aebbbb; background: #f7faf8; pointer-events: none; }
    .applications-results.is-loading { opacity: .5; pointer-events: none; }
    .applications-empty { padding: 70px 24px; text-align: center; }
    .applications-empty i { display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 15px; border-radius: 14px; color: var(--applications-teal); background: var(--applications-mint); font-size: 1.35rem; }
    .applications-empty h2 { margin: 0 0 8px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.3rem; }
    .applications-empty p { margin: 0 0 22px; color: var(--applications-muted); }
    .applications-empty .btn-primary { border: 0; border-radius: 8px; background: var(--applications-teal); font-weight: 700; }
    @media (max-width: 767px) { .applications-page { padding: 8px 0 42px; } .applications-toolbar { align-items: stretch; flex-direction: column; } .applications-search { width: 100%; } .applications-table { min-width: 700px; } .applications-table thead th, .applications-table tbody td { padding-right: 17px; padding-left: 17px; } .pagination-area { align-items: flex-start; flex-direction: column; } .pagination-nav { flex-wrap: wrap; } }
</style>
@endpush

@section('content')
<div class="container applications-page">
    <div class="applications-header"><span class="applications-kicker">Job seeker workspace</span><h1>My applications</h1><p>Track every opportunity and see where your applications stand.</p></div>
    <div class="applications-toolbar">
        <div class="applications-summary" data-applications-summary><strong>{{ $applications->total() }}</strong> {{ $applications->total() === 1 ? 'application' : 'applications' }}{{ !empty($search) ? ' matching your search' : '' }}</div>
        <form id="applications-search-form" class="applications-search" method="GET" action="{{ route('job-seeker.applications') }}">
            <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by job, company or location" aria-label="Search applications">
            <button type="submit"><i class="bi bi-search" aria-hidden="true"></i> Search</button>
        </form>
        @if(!empty($search))<a class="applications-clear" href="{{ route('job-seeker.applications') }}">Clear search</a>@endif
    </div>
    <div id="applications-results" class="applications-results">@include('job-seeker._applications_results')</div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('applications-search-form');
    const results = document.getElementById('applications-results');
    const loadResults = async (url, pushState = true) => {
        results.classList.add('is-loading');
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            if (!response.ok) throw new Error('Unable to load applications');
            const markup = await response.text();
            const fragment = new DOMParser().parseFromString(markup, 'text/html');
            const nextSummary = fragment.querySelector('[data-applications-summary]');
            if (nextSummary) document.querySelector('[data-applications-summary]').innerHTML = nextSummary.innerHTML;
            results.innerHTML = markup;
            if (pushState) history.pushState({}, '', url);
        } catch (error) { window.location.href = url; }
        finally { results.classList.remove('is-loading'); }
    };
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = new URL(form.action, window.location.origin);
        new FormData(form).forEach((value, key) => value && url.searchParams.set(key, value));
        loadResults(url.toString());
    });
    results.addEventListener('change', (event) => {
        if (event.target.id !== 'applications_per_page') return;
        const url = new URL(event.target.form.action, window.location.origin);
        new FormData(event.target.form).forEach((value, key) => value && url.searchParams.set(key, value));
        loadResults(url.toString());
    });
    results.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination-link');
        if (!link || link.classList.contains('is-disabled')) return;
        event.preventDefault(); loadResults(link.href);
    });
    window.addEventListener('popstate', () => loadResults(window.location.href, false));
})();
</script>
@endpush