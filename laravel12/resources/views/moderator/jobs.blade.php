@extends('layouts.app')

@section('title', 'Review Jobs')

@push('styles')
<style>
    :root { --mod-green: #087f67; }
    .moderator-jobs { --mod-ink: #18232d; --mod-muted: #6d7a80; --mod-line: #dce5e4; --mod-mint: #ccefe3; --mod-green: #087f67; --mod-coral: #f27864; padding: 42px 0 64px; color: var(--mod-ink); }
    .jobs-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 28px; }
    .jobs-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: var(--mod-green); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .jobs-kicker::before { content: ''; width: 22px; height: 2px; background: var(--mod-coral); }
    .jobs-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .jobs-header p { margin: 8px 0 0; color: var(--mod-muted); }
    .jobs-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 14px; }
    .jobs-search { display: flex; width: min(100%, 390px); }
    .jobs-search input { min-width: 0; flex: 1; border: 1px solid var(--mod-line); border-right: 0; border-radius: 9px 0 0 9px; padding: 11px 13px; color: var(--mod-ink); background: #fff; outline: 0; font: inherit; font-size: .82rem; }
    .jobs-search button { display: inline-flex; align-items: center; gap: 7px; border: 1px solid var(--mod-green); border-radius: 0 9px 9px 0; padding: 0 15px; color: #fff; background: var(--mod-green); cursor: pointer; font: 700 .78rem 'DM Sans', sans-serif; }
    .status-filter { border: 1px solid var(--mod-line); padding: 0 10px; color: var(--mod-ink); background: #fff; font: inherit; font-size: .82rem; outline: 0; cursor: pointer; }
    .status-filter:focus { border-color: var(--mod-green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .clear-search { color: var(--mod-muted); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .jobs-summary { color: var(--mod-muted); font-size: .82rem; }
    .jobs-summary strong { color: var(--mod-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .jobs-card { border: 1px solid var(--mod-line); border-radius: 12px; background: #fff; overflow: hidden; }
    .jobs-table-wrap { overflow-x: auto; }
    .jobs-table { min-width: 980px; margin: 0; }
    .jobs-table thead th { padding: 14px 22px; border: 0; background: #f7faf8; color: var(--mod-muted); font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; }
    .jobs-table tbody td { padding: 18px 22px; border-color: #edf2f0; color: var(--mod-muted); font-size: .84rem; }
    .job-name { display: block; color: var(--mod-ink); font-weight: 700; }
    .job-meta { display: block; margin-top: 4px; color: var(--mod-muted); font-size: .72rem; }
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700; }
    .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-pending { color: #ad7111; background: #fff5d9; }
    .status-approved { color: #087f67; background: #e6f7f0; }
    .status-rejected { color: #c64c3d; background: #fff0ed; }
    .action-buttons { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .action-btn { appearance: none; display: inline-flex; align-items: center; justify-content: center; border: 0; border-radius: 8px; padding: 9px 12px; color: #fff !important; font: 700 .75rem 'DM Sans', sans-serif; line-height: 1; cursor: pointer; opacity: 1; visibility: visible; }
    .view-job-btn { width: 34px; height: 34px; padding: 0; border: 1px solid var(--mod-line); color: var(--mod-green) !important; background: #fff; font-size: .95rem; }
    .view-job-btn:hover { color: #fff !important; background: var(--mod-green); }
    .approve-btn { background: #087f67 !important; }
    .reject-btn { background: #c64c3d !important; }
    .empty-state { padding: 70px 24px; text-align: center; }
    .empty-icon { display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 15px; border-radius: 14px; color: var(--mod-green); background: var(--mod-mint); }
    .empty-state h2 { margin: 0 0 7px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; }
    .empty-state p { margin: 0; color: var(--mod-muted); }
    .pagination-wrap { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 22px; border-top: 1px solid var(--mod-line); }
    .per-page-form { display: flex; align-items: center; gap: 9px; color: var(--mod-muted); font-size: .78rem; white-space: nowrap; }
    .per-page-form select { border: 1px solid var(--mod-line); border-radius: 7px; padding: 7px 28px 7px 9px; color: var(--mod-ink); background: #fff; font: inherit; outline: 0; cursor: pointer; }
    .pagination-info { color: var(--mod-muted); font-size: .78rem; }
    .pagination-nav { display: flex; align-items: center; gap: 5px; }
    .pagination-link { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px; border: 1px solid var(--mod-line); border-radius: 7px; color: var(--mod-green); background: #fff; font-size: .78rem; font-weight: 700; text-decoration: none; }
    .pagination-link.is-current { border-color: var(--mod-green); color: #fff; background: var(--mod-green); }
    .pagination-link.is-disabled { color: #aebbbb; background: #f7faf8; pointer-events: none; }
    .jobs-results.is-loading { opacity: .5; pointer-events: none; }
    .moderation-notice { position: fixed; top: 22px; right: 22px; z-index: 1100; display: flex; align-items: center; gap: 9px; width: min(360px, calc(100vw - 44px)); padding: 14px 17px; border: 1px solid #b9e7d5; border-radius: 10px; color: #087f67; background: #eaf8f2; box-shadow: 0 12px 30px rgba(24,35,45,.16); font-size: .84rem; font-weight: 700; opacity: 0; transform: translateX(calc(100% + 22px)); pointer-events: none; transition: opacity .25s ease, transform .35s cubic-bezier(.22,1,.36,1); }
    .moderation-notice.is-visible { opacity: 1; transform: translateX(0); }
    .job-view-modal { position: fixed; inset: 0; z-index: 1050; display: none; place-items: center; padding: 20px; background: rgba(24,35,45,.48); }
    .job-view-modal.is-open { display: grid; }
    .job-view-dialog { width: min(100%, 700px); max-height: min(760px, 90vh); overflow-y: auto; border-radius: 14px; background: #fff; box-shadow: 0 20px 60px rgba(24,35,45,.2); }
    .job-view-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 18px; padding: 24px; border-bottom: 1px solid var(--mod-line); }
    .job-view-header h2 { margin: 0; color: var(--mod-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.4rem; }
    .job-view-header p { margin: 5px 0 0; color: var(--mod-muted); font-size: .8rem; }
    .job-view-close { border: 0; color: var(--mod-muted); background: transparent; font-size: 1.25rem; cursor: pointer; }
    .job-view-body { padding: 24px; }
    .job-view-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 24px; }
    .job-view-detail { margin-bottom: 18px; }
    .job-view-label { display: block; margin-bottom: 5px; color: var(--mod-muted); font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .job-view-value { color: var(--mod-ink); white-space: pre-line; }
    .job-view-actions { display: flex; justify-content: flex-end; padding: 18px 24px; border-top: 1px solid var(--mod-line); }
    @media (max-width: 620px) { .moderation-notice { top: 14px; right: 14px; width: calc(100vw - 28px); } .job-view-modal { align-items: end; padding: 0; } .job-view-dialog { width: 100%; max-height: 92vh; border-radius: 14px 14px 0 0; } .job-view-header, .job-view-body { padding: 20px; } .job-view-grid { grid-template-columns: 1fr; gap: 0; } .job-view-actions { padding: 16px 20px 20px; } }
    @media (max-width: 620px) { .moderator-jobs { padding: 28px 0 44px; } .jobs-header { align-items: flex-start; flex-direction: column; } .jobs-toolbar { align-items: stretch; flex-direction: column; } .jobs-search { width: 100%; } .pagination-wrap { align-items: flex-start; flex-direction: column; padding-right: 17px; padding-left: 17px; } .pagination-nav { flex-wrap: wrap; } }
</style>
@endpush

@section('content')
<div class="container moderator-jobs">
    <div class="jobs-header">
        <div>
            <span class="jobs-kicker">Moderator workspace</span>
            <h1>Review job submissions</h1>
            <p>Approve or reject jobs based on quality, compliance, and posting readiness.</p>
        </div>
    </div>

    <div class="jobs-toolbar">
        <div class="jobs-summary" data-results-summary><strong>{{ $jobs->total() }}</strong> {{ $jobs->total() === 1 ? 'job' : 'jobs' }}{{ !empty($search) ? ' matching your search' : ' in the review queue' }}</div>
        <form class="jobs-search" method="GET" action="{{ route('moderator.jobs') }}">
            <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search by title, location or keyword" aria-label="Search jobs">
            <select class="status-filter" name="status" aria-label="Filter jobs by status">
                <option value="all" {{ ($status ?? 'pending') === '' ? 'selected' : '' }}>All statuses</option>
                <option value="pending" {{ ($status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit"><i class="bi bi-search" aria-hidden="true"></i> Search</button>
        </form>
        @if(!empty($search) || ($status ?? 'pending') !== 'pending')<a class="clear-search" href="{{ route('moderator.jobs') }}">Clear filters</a>@endif
    </div>
    <div id="jobs-results" class="jobs-results">@include('moderator._jobs_results')</div>
</div>
<div id="moderation-notice" class="moderation-notice" role="status"><i class="bi bi-check-circle" aria-hidden="true"></i><span></span></div>
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
        <div class="job-view-actions"><button type="button" class="job-view-close action-btn approve-btn">Close details</button></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const results = document.getElementById('jobs-results');
    const searchForm = document.querySelector('.moderator-jobs .jobs-search');
    const notice = document.getElementById('moderation-notice');
    const jobViewModal = document.getElementById('job-view-modal');
    const setJobView = (button) => {
        const fields = ['title', 'company', 'location', 'category', 'type', 'salary', 'deadline', 'posted', 'status', 'applications', 'description', 'requirements'];
        fields.forEach((field) => {
            const value = field === 'salary'
                ? (button.dataset.jobSalaryMin && button.dataset.jobSalaryMax ? `${button.dataset.jobSalaryMin} - ${button.dataset.jobSalaryMax}` : button.dataset.jobSalaryMin || button.dataset.jobSalaryMax || 'Not specified')
                : button.dataset[`job${field.charAt(0).toUpperCase()}${field.slice(1)}`] || 'Not specified';
            document.getElementById(`job-view-${field === 'title' ? 'title' : field}`).textContent = value;
        });
        jobViewModal.classList.add('is-open');
    };
    const closeJobView = () => jobViewModal.classList.remove('is-open');
    const loadResults = async (url, pushState = true) => {
        results.classList.add('is-loading');
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            if (!response.ok) throw new Error('Unable to load jobs');
            const markup = await response.text();
            const fragment = new DOMParser().parseFromString(markup, 'text/html');
            const nextSummary = fragment.querySelector('[data-results-summary]');
            if (nextSummary) document.querySelector('[data-results-summary]').innerHTML = nextSummary.innerHTML;
            results.innerHTML = markup;
            if (pushState) history.pushState({}, '', url);
        } catch (error) { window.location.href = url; }
        finally { results.classList.remove('is-loading'); }
    };
    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = new URL(searchForm.action, window.location.origin);
        new FormData(searchForm).forEach((value, key) => value && url.searchParams.set(key, value));
        const perPage = results.querySelector('#jobs_per_page')?.value;
        if (perPage) url.searchParams.set('per_page', perPage);
        loadResults(url.toString());
    });
    results.addEventListener('change', (event) => {
        if (event.target.id !== 'jobs_per_page') return;
        const url = new URL(event.target.form.action, window.location.origin);
        new FormData(event.target.form).forEach((value, key) => value && url.searchParams.set(key, value));
        loadResults(url.toString());
    });
    results.addEventListener('submit', async (event) => {
        const form = event.target.closest('.moderation-form');
        if (!form) return;
        event.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Unable to update job');
            const payload = await response.json();
            notice.querySelector('span').textContent = payload.message || 'Job status updated successfully.';
            notice.classList.add('is-visible');
            window.setTimeout(() => notice.classList.remove('is-visible'), 4500);
            await loadResults(window.location.href, false);
        } catch (error) { window.location.reload(); }
        finally { button.disabled = false; }
    });
    results.addEventListener('click', (event) => {
        const viewButton = event.target.closest('.view-job-btn');
        if (viewButton) {
            setJobView(viewButton);
            return;
        }
        const link = event.target.closest('.pagination-link');
        if (!link || link.classList.contains('is-disabled')) return;
        event.preventDefault(); loadResults(link.href);
    });
    jobViewModal.addEventListener('click', (event) => {
        if (event.target === jobViewModal || event.target.closest('.job-view-close')) closeJobView();
    });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeJobView(); });
    window.addEventListener('popstate', () => loadResults(window.location.href, false));
})();
</script>
@endpush