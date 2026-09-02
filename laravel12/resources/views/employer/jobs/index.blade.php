@extends('layouts.app')

@section('title', 'My Jobs')

@push('styles')
<style>
    .jobs-page { --jobs-ink: #18232d; --jobs-muted: #6d7a80; --jobs-line: #dce5e4; --jobs-mint: #ccefe3; --jobs-green: #087f67; --jobs-coral: #f27864; padding: 42px 0 64px; color: var(--jobs-ink); }
    .jobs-page-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 28px; }
    .jobs-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: var(--jobs-green); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .jobs-kicker::before { content: ''; width: 22px; height: 2px; background: var(--jobs-coral); }
    .jobs-page-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .jobs-page-header p { margin: 8px 0 0; color: var(--jobs-muted); }
    .jobs-page-action { display: inline-flex; align-items: center; gap: 9px; flex-shrink: 0; padding: 13px 18px; border-radius: 10px; color: #fff; background: var(--jobs-green); font-size: .86rem; font-weight: 700; text-decoration: none; transition: background .2s; }
    .jobs-page-action:hover { color: #fff; background: #05634f; text-decoration: none; }
    .jobs-page-action i { font-size: .8rem; }
    .jobs-summary { display: flex; align-items: center; gap: 10px; margin: 0; color: var(--jobs-muted); font-size: .82rem; }
    .jobs-summary strong { color: var(--jobs-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1rem; }
    .jobs-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 14px; }
    .jobs-toolbar .jobs-search { margin-left: auto; }
    .jobs-search { display: flex; width: min(100%, 390px); }
    .jobs-search input { min-width: 0; flex: 1; border: 1px solid var(--jobs-line); border-right: 0; border-radius: 9px 0 0 9px; padding: 11px 13px; color: var(--jobs-ink); background: #fff; outline: 0; font: inherit; font-size: .82rem; }
    .jobs-search input:focus { border-color: var(--jobs-green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .jobs-search button { display: inline-flex; align-items: center; gap: 7px; border: 1px solid var(--jobs-green); border-radius: 0 9px 9px 0; padding: 0 15px; color: #fff; background: var(--jobs-green); cursor: pointer; font: 700 .78rem 'DM Sans', sans-serif; }
    .jobs-search button:hover { background: #05634f; }
    .clear-search { color: var(--jobs-muted); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .clear-search:hover { color: var(--jobs-green); }
    .jobs-table-card { border: 1px solid var(--jobs-line); border-radius: 12px; background: #fff; overflow: hidden; }
    .jobs-table-wrap { overflow-x: auto; }
    .jobs-table { min-width: 850px; margin: 0; vertical-align: middle; }
    .jobs-table thead th { padding: 14px 22px; border: 0; color: var(--jobs-muted); background: #f7faf8; font-size: .69rem; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap; }
    .jobs-table tbody td { padding: 18px 22px; border-color: #edf2f0; color: var(--jobs-muted); font-size: .84rem; }
    .jobs-table tbody tr:last-child td { border-bottom: 0; }
    .job-name { display: block; color: var(--jobs-ink); font-weight: 700; }
    .job-meta { display: block; margin-top: 4px; color: var(--jobs-muted); font-size: .73rem; }
    .job-meta i { margin-right: 4px; color: var(--jobs-green); }
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700; }
    .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-approved { color: #087f67; background: #e6f7f0; }
    .status-pending { color: #ad7111; background: #fff5d9; }
    .status-rejected { color: #c64c3d; background: #fff0ed; }
    .type-label { display: inline-flex; align-items: center; gap: 6px; color: var(--jobs-ink); font-size: .78rem; text-transform: capitalize; }
    .type-label i { color: var(--jobs-green); }
    .table-action { display: inline-flex; align-items: center; gap: 7px; margin-right: 14px; color: var(--jobs-green); font-size: .78rem; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .table-action:last-child { margin-right: 0; }
    .table-action:hover { color: #05634f; text-decoration: underline; }
    .table-delete-action { border: 0; padding: 0; color: #c64c3d; background: transparent; cursor: pointer; font: inherit; }
    .table-delete-action:hover { color: #a33b30; }
    .table-action i { font-size: .74rem; }
    .empty-state { padding: 70px 24px; text-align: center; }
    .empty-icon { display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 15px; border-radius: 14px; color: var(--jobs-green); background: var(--jobs-mint); }
    .empty-state h2 { margin: 0 0 7px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; }
    .empty-state p { margin: 0 0 22px; color: var(--jobs-muted); font-size: .88rem; }
    .pagination-wrap { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 22px; border-top: 1px solid var(--jobs-line); }
    .per-page-form { display: flex; align-items: center; gap: 9px; color: var(--jobs-muted); font-size: .78rem; white-space: nowrap; }
    .per-page-form select { border: 1px solid var(--jobs-line); border-radius: 7px; padding: 7px 28px 7px 9px; color: var(--jobs-ink); background: #fff; font: inherit; outline: 0; cursor: pointer; }
    .per-page-form select:focus { border-color: var(--jobs-green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .pagination-info { color: var(--jobs-muted); font-size: .78rem; }
    .pagination-nav { display: flex; align-items: center; gap: 5px; }
    .pagination-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px; border: 1px solid var(--jobs-line); border-radius: 7px; color: var(--jobs-green); background: #fff; font-size: .78rem; font-weight: 700; text-decoration: none; }
    .pagination-link:hover { border-color: var(--jobs-green); color: var(--jobs-green); background: #eaf8f2; text-decoration: none; }
    .pagination-link.is-current { border-color: var(--jobs-green); color: #fff; background: var(--jobs-green); }
    .pagination-link.is-disabled { color: #aebbbb; background: #f7faf8; cursor: not-allowed; pointer-events: none; }
    .jobs-results.is-loading { opacity: .5; pointer-events: none; transition: opacity .2s; }
    .delete-modal { display: none; position: fixed; inset: 0; z-index: 1050; place-items: center; padding: 20px; background: rgba(24,35,45,.45); }
    .delete-modal.is-open { display: grid; }
    .delete-dialog { width: min(100%, 420px); padding: 26px; border-radius: 12px; background: #fff; box-shadow: 0 18px 55px rgba(24,35,45,.2); }
    .delete-dialog h2 { margin: 0 0 8px; color: var(--jobs-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; }
    .delete-dialog p { margin: 0; color: var(--jobs-muted); font-size: .88rem; line-height: 1.55; }
    .delete-dialog strong { color: var(--jobs-ink); }
    .delete-dialog-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
    .delete-dialog-button { border: 0; border-radius: 8px; padding: 10px 15px; cursor: pointer; font: 700 .8rem 'DM Sans', sans-serif; }
    .delete-dialog-cancel { color: var(--jobs-ink); background: #edf2f0; }
    .delete-dialog-confirm { color: #fff; background: #c64c3d; }
    .delete-dialog-confirm:hover { background: #a33b30; }
    .jobs-table tr.is-removing { opacity: 0; transition: opacity .2s; }
    .delete-toast { position: fixed; top: 22px; right: 22px; z-index: 1060; display: flex; align-items: flex-start; gap: 11px; width: min(360px, calc(100vw - 44px)); padding: 15px 17px; border: 1px solid #bde8d8; border-radius: 10px; color: #075b4b; background: #effbf6; box-shadow: 0 12px 32px rgba(24,35,45,.16); opacity: 0; transform: translateX(120%); pointer-events: none; }
    .delete-toast.is-visible { animation: delete-toast-in .35s ease-out forwards, delete-toast-out .35s ease-in 3.2s forwards; }
    .delete-toast i { color: var(--jobs-green); font-size: 1.1rem; }
    .delete-toast strong { display: block; margin-bottom: 2px; color: var(--jobs-ink); font-size: .82rem; }
    .delete-toast span { display: block; font-size: .76rem; }
    @keyframes delete-toast-in { to { opacity: 1; transform: translateX(0); } }
    @keyframes delete-toast-out { to { opacity: 0; transform: translateX(120%); } }
    @media (max-width: 620px) { .jobs-page { padding: 28px 0 44px; } .jobs-page-header { align-items: flex-start; flex-direction: column; gap: 15px; } .jobs-page-action { justify-content: center; width: 100%; } .jobs-toolbar { align-items: stretch; flex-direction: column; gap: 10px; } .jobs-search { width: 100%; } .jobs-table { min-width: 760px; } .jobs-table thead th, .jobs-table tbody td { padding-right: 17px; padding-left: 17px; } .pagination-wrap { align-items: flex-start; flex-direction: column; padding-right: 17px; padding-left: 17px; } .pagination-nav { flex-wrap: wrap; } }
</style>
@endpush

@section('content')
<div class="container jobs-page">
    <div class="jobs-page-header">
        <div><span class="jobs-kicker">Employer workspace</span><h1>My posted jobs</h1><p>Manage your open roles and keep track of candidate interest.</p></div>
        <a href="{{ route('employer.jobs.create') }}" class="jobs-page-action"><i class="bi bi-plus-lg" aria-hidden="true"></i> Post a new job</a>
    </div>

    <div class="jobs-toolbar">
        <div class="jobs-summary" data-jobs-summary>
            <strong>{{ $jobs->total() }}</strong> {{ $jobs->total() === 1 ? 'job' : 'jobs' }}{{ !empty($search) ? ' matching your search' : ' in your hiring workspace' }}
        </div>
        <form class="jobs-search" method="GET" action="{{ route('employer.jobs') }}">
            <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by title, location or keyword" aria-label="Search jobs">
            <button type="submit"><i class="bi bi-search" aria-hidden="true"></i> Search</button>
        </form>
        @if(!empty($search))<a class="clear-search" href="{{ route('employer.jobs') }}">Clear search</a>@endif
    </div>

    <div id="jobs-results" class="jobs-results">@include('employer.jobs._results')</div>
</div>

<div class="delete-modal" id="delete-modal" role="dialog" aria-modal="true" aria-labelledby="delete-dialog-title" aria-describedby="delete-dialog-message">
    <div class="delete-dialog">
        <h2 id="delete-dialog-title">Delete this job?</h2>
        <p id="delete-dialog-message">This will remove <strong id="delete-job-title"></strong> from your jobs list.</p>
        <div class="delete-dialog-actions">
            <button class="delete-dialog-button delete-dialog-cancel" type="button" data-delete-cancel>Cancel</button>
            <button class="delete-dialog-button delete-dialog-confirm" type="button" data-delete-confirm>Delete job</button>
        </div>
    </div>
</div>
<div class="delete-toast" id="delete-toast" role="status" aria-live="polite">
    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
    <div><strong>Job deleted</strong><span id="delete-toast-message">The job was removed successfully.</span></div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const results = document.getElementById('jobs-results');
    const searchForm = document.querySelector('.jobs-search');
    const deleteModal = document.getElementById('delete-modal');
    const deleteTitle = document.getElementById('delete-job-title');
    const deleteConfirm = document.querySelector('[data-delete-confirm]');
    const deleteToast = document.getElementById('delete-toast');
    const deleteToastMessage = document.getElementById('delete-toast-message');
    let pendingDelete = null;

    const loadJobs = async (url, pushState = true) => {
        results.classList.add('is-loading');
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            if (!response.ok) throw new Error('Unable to load jobs');
            results.innerHTML = await response.text();
            const total = results.querySelector('[data-jobs-total]')?.dataset.jobsTotal ?? '0';
            const summary = document.querySelector('[data-jobs-summary]');
            if (summary) {
                summary.innerHTML = `<strong>${total}</strong> ${total === '1' ? 'job' : 'jobs'}${url.searchParams.get('search') ? ' matching your search' : ' in your hiring workspace'}`;
            }
            if (pushState) history.pushState({}, '', url);
            results.querySelector('#per_page')?.focus({ preventScroll: true });
        } catch (error) {
            window.location.href = url;
        } finally {
            results.classList.remove('is-loading');
        }
    };

    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = new URL(searchForm.action, window.location.origin);
        const search = searchForm.querySelector('[name="search"]').value.trim();
        if (search) url.searchParams.set('search', search);
        const perPage = results.querySelector('#per_page')?.value;
        if (perPage) url.searchParams.set('per_page', perPage);
        loadJobs(url.toString());
    });

    document.addEventListener('change', (event) => {
        if (event.target.id !== 'per_page') return;
        const url = new URL(event.target.form.action, window.location.origin);
        new FormData(event.target.form).forEach((value, key) => value && url.searchParams.set(key, value));
        loadJobs(url.toString());
    });

    results.addEventListener('click', (event) => {
        const deleteButton = event.target.closest('[data-delete-url]');
        if (deleteButton) {
            pendingDelete = { button: deleteButton, url: deleteButton.dataset.deleteUrl };
            deleteTitle.textContent = deleteButton.dataset.jobTitle;
            deleteModal.classList.add('is-open');
            deleteConfirm.focus();
            return;
        }
        const link = event.target.closest('.pagination-link');
        if (!link || link.classList.contains('is-disabled')) return;
        event.preventDefault();
        loadJobs(link.href);
    });

    const closeDeleteModal = () => {
        deleteModal.classList.remove('is-open');
        pendingDelete = null;
    };

    document.querySelector('[data-delete-cancel]').addEventListener('click', closeDeleteModal);
    deleteModal.addEventListener('click', (event) => {
        if (event.target === deleteModal) closeDeleteModal();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && deleteModal.classList.contains('is-open')) closeDeleteModal();
    });

    deleteConfirm.addEventListener('click', async () => {
        if (!pendingDelete) return;
        deleteConfirm.disabled = true;
        try {
            const response = await fetch(pendingDelete.url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Unable to delete job');
            const row = pendingDelete.button.closest('tr');
            row.classList.add('is-removing');
            window.setTimeout(() => {
                row.remove();
                closeDeleteModal();
                deleteConfirm.disabled = false;
                deleteToastMessage.textContent = `${deleteTitle.textContent} was removed successfully.`;
                deleteToast.classList.remove('is-visible');
                void deleteToast.offsetWidth;
                deleteToast.classList.add('is-visible');
            }, 200);
        } catch (error) {
            deleteConfirm.disabled = false;
            window.alert('The job could not be deleted. Please try again.');
        }
    });

    window.addEventListener('popstate', () => loadJobs(window.location.href, false));
})();
</script>
@endpush
