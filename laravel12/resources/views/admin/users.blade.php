@extends('layouts.app')

@section('title', 'Manage Users')

@push('styles')
<style>
    .admin-users { --users-ink: #18232d; --users-muted: #6d7a80; --users-line: #dce5e4; --users-teal: #087f67; --users-mint: #ccefe3; padding: 18px 0 54px; color: var(--users-ink); }
    .users-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 27px; }
    .users-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 9px; color: var(--users-teal); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .users-kicker::before { width: 22px; height: 2px; background: #f27864; content: ''; }
    .users-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .users-header p { margin: 8px 0 0; color: var(--users-muted); }
    .users-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 15px; }
    .users-summary { color: var(--users-muted); font-size: .82rem; }
    .users-summary strong { color: var(--users-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .users-search { display: flex; width: min(100%, 410px); }
    .users-search input { min-width: 0; flex: 1; border: 1px solid var(--users-line); border-right: 0; border-radius: 9px 0 0 9px; padding: 11px 13px; color: var(--users-ink); outline: 0; font: inherit; font-size: .82rem; }
    .users-search input:focus { border-color: var(--users-teal); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .users-search button { border: 1px solid var(--users-teal); border-radius: 0 9px 9px 0; padding: 0 15px; color: #fff; background: var(--users-teal); cursor: pointer; font: 700 .78rem 'DM Sans', sans-serif; }
    .users-clear { color: var(--users-muted); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .users-card { overflow: hidden; border: 1px solid var(--users-line); border-radius: 12px; background: #fff; box-shadow: none; }
    .users-card .table-responsive { overflow-x: auto; }
    .users-table { min-width: 900px; margin: 0; vertical-align: middle; }
    .users-table thead th { padding: 14px 22px; border: 0; background: #f7faf8; color: var(--users-muted); font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap; }
    .users-table tbody td { padding: 17px 22px; border-color: #edf2f0; color: var(--users-muted); font-size: .82rem; }
    .users-table tbody tr:last-child td { border-bottom: 0; }
    .user-name { display: block; color: var(--users-ink); font-weight: 700; }
    .user-email { display: block; margin-top: 3px; color: var(--users-muted); font-size: .74rem; }
    .user-badge { display: inline-flex; align-items: center; gap: 6px; border-radius: 999px; padding: 6px 10px; font-size: .7rem; font-weight: 700; }
    .user-role { color: var(--users-teal); background: var(--users-mint); }
    .user-status-active { color: #087f67; background: #e6f7f0; }
    .user-status-suspended { color: #c64c3d; background: #fff0ed; }
    .user-controls { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .user-controls form { margin: 0; }
    .user-controls .btn { border-radius: 7px; padding: 7px 10px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
    .role-form { display: flex; gap: 6px; }
    .role-form select { min-width: 112px; border: 1px solid var(--users-line); border-radius: 7px; padding: 6px 8px; color: var(--users-ink); font-size: .72rem; }
    .role-form .btn { border: 1px solid var(--users-teal); color: var(--users-teal); background: #fff; }
    .role-form .btn:hover { color: #fff; background: var(--users-teal); }
    .status-form .btn { border: 1px solid #d5a31a; color: #ad7111; background: #fffaf0; }
    .status-form .btn:hover { color: #fff; background: #ad7111; }
    .delete-form .btn { border: 1px solid #c64c3d; color: #c64c3d; background: #fff; }
    .delete-form .btn:hover { color: #fff; background: #c64c3d; }
    .users-empty { padding: 65px 24px; text-align: center; }
    .users-empty i { display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 14px; border-radius: 14px; color: var(--users-teal); background: var(--users-mint); font-size: 1.35rem; }
    .users-empty h2 { margin: 0 0 8px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; }
    .users-empty p { margin: 0; color: var(--users-muted); }
    .users-pagination { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 22px; border-top: 1px solid var(--users-line); }
    .per-page-form { display: flex; align-items: center; gap: 9px; color: var(--users-muted); font-size: .78rem; white-space: nowrap; }
    .per-page-form select { border: 1px solid var(--users-line); border-radius: 7px; padding: 7px 28px 7px 9px; color: var(--users-ink); background: #fff; font: inherit; outline: 0; cursor: pointer; }
    .pagination-info { color: var(--users-muted); font-size: .78rem; }
    .pagination-nav { display: flex; align-items: center; gap: 5px; }
    .pagination-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px; border: 1px solid var(--users-line); border-radius: 7px; color: var(--users-teal); background: #fff; font-size: .78rem; font-weight: 700; text-decoration: none; }
    .pagination-link.is-current { border-color: var(--users-teal); color: #fff; background: var(--users-teal); }
    .pagination-link.is-disabled { color: #aebbbb; background: #f7faf8; pointer-events: none; }
    .users-results.is-loading { opacity: .5; pointer-events: none; transition: opacity .2s; }
    @media (max-width: 620px) { .admin-users { padding: 8px 0 42px; } .users-header { align-items: flex-start; flex-direction: column; } .users-toolbar { align-items: stretch; flex-direction: column; } .users-search { width: 100%; } .users-table { min-width: 820px; } .users-table thead th, .users-table tbody td { padding-right: 17px; padding-left: 17px; } .users-pagination { align-items: flex-start; flex-direction: column; padding-right: 17px; padding-left: 17px; overflow-x: auto; } .pagination-nav { flex-wrap: wrap; } }
</style>
@endpush

@section('content')
<div class="container admin-users">
    <div class="users-header"><div><span class="users-kicker">Administration workspace</span><h1>Manage users</h1><p>Update roles, control account access, and keep the platform organized.</p></div></div>
    <div class="users-toolbar">
        <div class="users-summary" data-users-summary><strong>{{ $users->total() }}</strong> {{ $users->total() === 1 ? 'user' : 'users' }}{{ !empty($search) ? ' matching your search' : ' in the platform' }}</div>
        <form id="users-search-form" class="users-search" method="GET" action="{{ route('admin.users') }}"><input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by name, email, role or status" aria-label="Search users"><button type="submit"><i class="bi bi-search" aria-hidden="true"></i> Search</button></form>
        @if(!empty($search))<a class="users-clear" href="{{ route('admin.users') }}">Clear search</a>@endif
    </div>
    <div id="users-results" class="users-results">@include('admin._users_results')</div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('users-search-form');
    const results = document.getElementById('users-results');
    const loadResults = async (url, pushState = true) => {
        results.classList.add('is-loading');
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            if (!response.ok) throw new Error('Unable to load users');
            const markup = await response.text();
            const fragment = new DOMParser().parseFromString(markup, 'text/html');
            const nextSummary = fragment.querySelector('[data-users-summary]');
            if (nextSummary) document.querySelector('[data-users-summary]').innerHTML = nextSummary.innerHTML;
            results.innerHTML = markup;
            if (pushState) history.pushState({}, '', url);
        } catch (error) { window.location.href = url; }
        finally { results.classList.remove('is-loading'); }
    };
    form.addEventListener('submit', (event) => { event.preventDefault(); const url = new URL(form.action, window.location.origin); const search = form.querySelector('[name="search"]').value.trim(); if (search) url.searchParams.set('search', search); const perPage = results.querySelector('#users_per_page')?.value; if (perPage) url.searchParams.set('per_page', perPage); loadResults(url.toString()); });
    results.addEventListener('change', (event) => { if (event.target.id !== 'users_per_page') return; const url = new URL(event.target.form.action, window.location.origin); new FormData(event.target.form).forEach((value, key) => value && url.searchParams.set(key, value)); loadResults(url.toString()); });
    results.addEventListener('submit', (event) => { const form = event.target.closest('.role-form, .status-form, .delete-form'); if (!form || form.dataset.submitting === 'true') { if (form) event.preventDefault(); return; } form.dataset.submitting = 'true'; form.querySelector('button[type="submit"]').disabled = true; });
    results.addEventListener('click', (event) => { const link = event.target.closest('.pagination-link'); if (!link || link.classList.contains('is-disabled')) return; event.preventDefault(); loadResults(link.href); });
    window.addEventListener('popstate', () => loadResults(window.location.href, false));
})();
</script>
@endpush