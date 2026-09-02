@extends('layouts.app')

@section('title', 'Manage Jobs')

@push('styles')
<style>
    .admin-jobs { --jobs-ink: #18232d; --jobs-muted: #6d7a80; --jobs-line: #dce5e4; --jobs-teal: #087f67; --jobs-mint: #ccefe3; padding: 18px 0 54px; color: var(--jobs-ink); }
    .jobs-header { margin-bottom: 27px; }
    .jobs-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 9px; color: var(--jobs-teal); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .jobs-kicker::before { width: 22px; height: 2px; background: #f27864; content: ''; }
    .jobs-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .jobs-header p { margin: 8px 0 0; color: var(--jobs-muted); }
    .jobs-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 15px; }
    .jobs-summary { color: var(--jobs-muted); font-size: .82rem; }
    .jobs-summary strong { color: var(--jobs-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .jobs-search { display: flex; width: min(100%, 500px); }
    .jobs-search input { min-width: 0; flex: 1; border: 1px solid var(--jobs-line); border-right: 0; border-radius: 9px 0 0 9px; padding: 11px 13px; color: var(--jobs-ink); outline: 0; font: inherit; font-size: .82rem; }
    .jobs-search input:focus, .status-filter:focus { border-color: var(--jobs-teal); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .jobs-search button { border: 1px solid var(--jobs-teal); border-radius: 0 9px 9px 0; padding: 0 15px; color: #fff; background: var(--jobs-teal); cursor: pointer; font: 700 .78rem 'DM Sans', sans-serif; }
    .status-filter { border: 1px solid var(--jobs-line); padding: 0 8px; color: var(--jobs-ink); background: #fff; font-size: .78rem; outline: 0; }
    .jobs-clear { color: var(--jobs-muted); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .jobs-card { overflow: hidden; border: 1px solid var(--jobs-line); border-radius: 12px; background: #fff; box-shadow: none; }
    .jobs-table-wrap { overflow-x: auto; }
    .admin-jobs-table { min-width: 1050px; margin: 0; vertical-align: middle; }
    .admin-jobs-table thead th { padding: 14px 22px; border: 0; background: #f7faf8; color: var(--jobs-muted); font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap; }
    .admin-jobs-table tbody td { padding: 17px 22px; border-color: #edf2f0; color: var(--jobs-muted); font-size: .82rem; }
    .admin-jobs-table tbody tr:last-child td { border-bottom: 0; }
    .job-title { display: block; color: var(--jobs-ink); font-weight: 700; }
    .job-meta { display: block; margin-top: 4px; font-size: .73rem; }
    .job-meta i { margin-right: 5px; color: var(--jobs-teal); }
    .job-status { display: inline-flex; align-items: center; gap: 6px; border-radius: 999px; padding: 6px 10px; font-size: .7rem; font-weight: 700; }
    .job-status::before { width: 6px; height: 6px; border-radius: 50%; background: currentColor; content: ''; }
    .job-status-approved { color: #087f67; background: #e6f7f0; }
    .job-status-pending { color: #ad7111; background: #fff5d9; }
    .job-status-rejected { color: #c64c3d; background: #fff0ed; }
    .job-controls { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
    .job-controls form { margin: 0; }
    .job-btn { border: 1px solid transparent; border-radius: 7px; padding: 7px 10px; background: #fff; cursor: pointer; font-size: .72rem; font-weight: 700; white-space: nowrap; }
    .view-job-btn { display: inline-grid; place-items: center; width: 34px; height: 34px; padding: 0; border-color: var(--jobs-line); color: var(--jobs-teal); font-size: .95rem; }
    .view-job-btn:hover { color: #fff; background: var(--jobs-teal); }
    .approve-btn { border-color: var(--jobs-teal); color: var(--jobs-teal); }
    .approve-btn:hover { color: #fff; background: var(--jobs-teal); }
    .reject-btn, .delete-btn { border-color: #c64c3d; color: #c64c3d; }
    .reject-btn:hover, .delete-btn:hover { color: #fff; background: #c64c3d; }
    .jobs-pagination { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 22px; border-top: 1px solid var(--jobs-line); }
    .per-page-form { display: flex; align-items: center; gap: 9px; color: var(--jobs-muted); font-size: .78rem; white-space: nowrap; }
    .per-page-form select { border: 1px solid var(--jobs-line); border-radius: 7px; padding: 7px 28px 7px 9px; color: var(--jobs-ink); background: #fff; font: inherit; outline: 0; cursor: pointer; }
    .pagination-info { color: var(--jobs-muted); font-size: .78rem; }
    .pagination-nav { display: flex; align-items: center; gap: 5px; }
    .pagination-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px; border: 1px solid var(--jobs-line); border-radius: 7px; color: var(--jobs-teal); background: #fff; font-size: .78rem; font-weight: 700; text-decoration: none; }
    .pagination-link.is-current { border-color: var(--jobs-teal); color: #fff; background: var(--jobs-teal); }
    .pagination-link.is-disabled { color: #aebbbb; background: #f7faf8; pointer-events: none; }
    .jobs-results.is-loading { opacity: .5; pointer-events: none; }
    .jobs-empty { padding: 65px 24px; text-align: center; }
    .jobs-empty i { display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 14px; border-radius: 14px; color: var(--jobs-teal); background: var(--jobs-mint); font-size: 1.35rem; }
    .jobs-empty h2 { margin: 0 0 8px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; }
    .jobs-empty p { margin: 0; color: var(--jobs-muted); }
    .job-view-modal { position: fixed; inset: 0; z-index: 1050; display: none; place-items: center; padding: 20px; background: rgba(24,35,45,.48); }
    .job-view-modal.is-open { display: grid; }
    .job-view-dialog { width: min(100%, 700px); max-height: min(760px, 90vh); overflow-y: auto; border-radius: 14px; background: #fff; box-shadow: 0 20px 60px rgba(24,35,45,.2); }
    .job-view-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 18px; padding: 24px; border-bottom: 1px solid var(--jobs-line); }
    .job-view-header h2 { margin: 0; color: var(--jobs-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.4rem; }
    .job-view-header p { margin: 5px 0 0; color: var(--jobs-muted); font-size: .8rem; }
    .job-view-close { border: 0; color: var(--jobs-muted); background: transparent; font-size: 1.25rem; cursor: pointer; }
    .job-view-body { padding: 24px; }
    .job-view-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 24px; }
    .job-view-detail { margin-bottom: 18px; }
    .job-view-label { display: block; margin-bottom: 5px; color: var(--jobs-muted); font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .job-view-value { color: var(--jobs-ink); white-space: pre-line; }
    .job-view-actions { display: flex; justify-content: flex-end; padding: 18px 24px; border-top: 1px solid var(--jobs-line); }
    @media (max-width: 620px) { .admin-jobs { padding: 8px 0 42px; } .jobs-toolbar { align-items: stretch; flex-direction: column; } .jobs-search { width: 100%; } .jobs-pagination { align-items: flex-start; flex-direction: column; padding-right: 17px; padding-left: 17px; } .pagination-nav { flex-wrap: wrap; } .job-view-modal { align-items: end; padding: 0; } .job-view-dialog { width: 100%; max-height: 92vh; border-radius: 14px 14px 0 0; } .job-view-header, .job-view-body { padding: 20px; } .job-view-grid { grid-template-columns: 1fr; gap: 0; } .job-view-actions { padding: 16px 20px 20px; } }
</style>
@endpush

@section('content')
<div class="container admin-jobs">
    <div class="jobs-header"><span class="jobs-kicker">Administration workspace</span><h1>Manage jobs</h1><p>Review job quality, update publication status, and keep the marketplace healthy.</p></div>
    <div class="jobs-toolbar">
        <div class="jobs-summary" data-jobs-summary><strong>{{ $jobs->total() }}</strong> {{ $jobs->total() === 1 ? 'job' : 'jobs' }}{{ !empty($search) ? ' matching your search' : '' }}</div>
        <form id="jobs-search-form" class="jobs-search" method="GET" action="{{ route('admin.jobs') }}"><input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by title, company or location" aria-label="Search jobs"><select class="status-filter" name="status" aria-label="Filter by status"><option value="">All statuses</option><option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option><option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option><option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option></select><button type="submit"><i class="bi bi-search" aria-hidden="true"></i> Search</button></form>
        @if(!empty($search) || !empty($status))<a class="jobs-clear" href="{{ route('admin.jobs') }}">Clear filters</a>@endif
    </div>
    <div id="jobs-results" class="jobs-results">@include('admin._jobs_results')</div>
</div>
<div id="job-view-modal" class="job-view-modal" role="dialog" aria-modal="true" aria-labelledby="job-view-title">
    <div class="job-view-dialog">
        <div class="job-view-header"><div><h2 id="job-view-title"></h2><p id="job-view-company"></p></div><button type="button" class="job-view-close" aria-label="Close job details"><i class="bi bi-x-lg" aria-hidden="true"></i></button></div>
        <div class="job-view-body">
            <div class="job-view-grid">
                <div class="job-view-detail"><span class="job-view-label">Location</span><div id="job-view-location" class="job-view-value"></div></div>
                <div class="job-view-detail"><span class="job-view-label">Category</span><div id="job-view-category" class="job-view-value"></div></div>
                <div class="job-view-detail"><span class="job-view-label">Job type</span><div id="job-view-type" class="job-view-value"></div></div>
                <div class="job-view-detail"><span class="job-view-label">Salary</span><div id="job-view-salary" class="job-view-value"></div></div>
                <div class="job-view-detail"><span class="job-view-label">Deadline</span><div id="job-view-deadline" class="job-view-value"></div></div>
                <div class="job-view-detail"><span class="job-view-label">Posted</span><div id="job-view-posted" class="job-view-value"></div></div>
                <div class="job-view-detail"><span class="job-view-label">Status</span><div id="job-view-status" class="job-view-value"></div></div>
                <div class="job-view-detail"><span class="job-view-label">Applications</span><div id="job-view-applications" class="job-view-value"></div></div>
            </div>
            <div class="job-view-detail"><span class="job-view-label">Description</span><div id="job-view-description" class="job-view-value"></div></div>
            <div class="job-view-detail"><span class="job-view-label">Requirements</span><div id="job-view-requirements" class="job-view-value"></div></div>
        </div>
        <div class="job-view-actions"><button type="button" class="job-btn job-view-close">Close details</button></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('jobs-search-form');
    const results = document.getElementById('jobs-results');
    const jobViewModal = document.getElementById('job-view-modal');
    const setJobView = (button) => {
        const fields = ['title', 'company', 'location', 'category', 'type', 'deadline', 'posted', 'status', 'applications', 'description', 'requirements'];
        fields.forEach((field) => {
            const dataName = `job${field.charAt(0).toUpperCase()}${field.slice(1)}`;
            const value = field === 'salary'
                ? (button.dataset.jobSalaryMin && button.dataset.jobSalaryMax ? `${button.dataset.jobSalaryMin} - ${button.dataset.jobSalaryMax}` : button.dataset.jobSalaryMin || button.dataset.jobSalaryMax || 'Not specified')
                : button.dataset[dataName] || 'Not specified';
            document.getElementById(`job-view-${field}`).textContent = value;
        });
        const salary = button.dataset.jobSalaryMin && button.dataset.jobSalaryMax ? `${button.dataset.jobSalaryMin} - ${button.dataset.jobSalaryMax}` : button.dataset.jobSalaryMin || button.dataset.jobSalaryMax || 'Not specified';
        document.getElementById('job-view-salary').textContent = salary;
        jobViewModal.classList.add('is-open');
    };
    const closeJobView = () => jobViewModal.classList.remove('is-open');
    const loadResults = async (url, pushState = true) => {
        results.classList.add('is-loading');
        try { const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } }); if (!response.ok) throw new Error('Unable to load jobs'); const markup = await response.text(); const fragment = new DOMParser().parseFromString(markup, 'text/html'); const nextSummary = fragment.querySelector('[data-jobs-summary]'); if (nextSummary) document.querySelector('[data-jobs-summary]').innerHTML = nextSummary.innerHTML; results.innerHTML = markup; if (pushState) history.pushState({}, '', url); } catch (error) { window.location.href = url; } finally { results.classList.remove('is-loading'); }
    };
    form.addEventListener('submit', (event) => { event.preventDefault(); const url = new URL(form.action, window.location.origin); new FormData(form).forEach((value, key) => value && url.searchParams.set(key, value)); const perPage = results.querySelector('#jobs_per_page')?.value; if (perPage) url.searchParams.set('per_page', perPage); loadResults(url.toString()); });
    results.addEventListener('change', (event) => { if (event.target.id !== 'jobs_per_page') return; const url = new URL(event.target.form.action, window.location.origin); new FormData(event.target.form).forEach((value, key) => value && url.searchParams.set(key, value)); loadResults(url.toString()); });
    results.addEventListener('submit', (event) => { const form = event.target.closest('.job-action-form'); if (!form || form.dataset.submitting === 'true') { if (form) event.preventDefault(); return; } form.dataset.submitting = 'true'; form.querySelector('button[type="submit"]').disabled = true; });
    results.addEventListener('click', (event) => { const link = event.target.closest('.pagination-link'); if (!link || link.classList.contains('is-disabled')) return; event.preventDefault(); loadResults(link.href); });
    results.addEventListener('click', (event) => { const viewButton = event.target.closest('.view-job-btn'); if (viewButton) setJobView(viewButton); });
    jobViewModal.addEventListener('click', (event) => { if (event.target === jobViewModal || event.target.closest('.job-view-close')) closeJobView(); });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeJobView(); });
    window.addEventListener('popstate', () => loadResults(window.location.href, false));
})();
</script>
@endpush
