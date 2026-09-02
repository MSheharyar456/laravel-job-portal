@extends('layouts.app')

@section('title', 'Moderator Dashboard')

@push('styles')
<style>
    :root { --mod-green: #087f67; }
    .moderator-dashboard { --mod-ink: #18232d; --mod-muted: #6d7a80; --mod-line: #dce5e4; --mod-mint: #ccefe3; --mod-green: #087f67; --mod-coral: #f27864; padding: 42px 0 64px; color: var(--mod-ink); }
    .dashboard-hero { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 30px; }
    .dashboard-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: var(--mod-green); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .dashboard-kicker::before { content: ''; width: 22px; height: 2px; background: var(--mod-coral); }
    .dashboard-hero h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3.1rem); letter-spacing: -.05em; }
    .dashboard-hero p { margin: 8px 0 0; color: var(--mod-muted); }
    .dashboard-action { display: inline-flex; align-items: center; gap: 9px; flex-shrink: 0; padding: 13px 18px; border-radius: 10px; background: var(--mod-green); color: #fff; font-weight: 700; text-decoration: none; transition: background .2s; }
    .dashboard-action:hover { background: #05634f; color: #fff; text-decoration: none; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 34px; }
    .metric-card { position: relative; min-height: 138px; padding: 22px; overflow: hidden; border: 1px solid var(--mod-line); border-radius: 12px; background: #fff; }
    .metric-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .metric-icon { display: grid; place-items: center; width: 40px; height: 40px; border-radius: 10px; background: var(--mod-mint); color: var(--mod-green); font-size: 1.1rem; }
    .metric-card:nth-child(2) .metric-icon { background: #eaf2ff; color: #2355d0; }
    .metric-card:nth-child(3) .metric-icon { background: #fff1ed; color: #d9534f; }
    .metric-card:nth-child(4) .metric-icon { background: #f2ecff; color: #6d53d8; }
    .metric-label { position: relative; z-index: 1; color: var(--mod-muted); font-size: .78rem; font-weight: 700; }
    .metric-value { position: relative; z-index: 1; display: block; margin-top: 14px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 2.45rem; line-height: 1; letter-spacing: -.05em; }
    .jobs-section { border: 1px solid var(--mod-line); border-radius: 12px; background: #fff; overflow: hidden; }
    .section-heading { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 22px 24px; border-bottom: 1px solid var(--mod-line); }
    .section-heading h2 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; letter-spacing: -.03em; }
    .section-heading a { color: var(--mod-green); font-size: .82rem; font-weight: 700; text-decoration: none; }
    .section-heading a:hover { text-decoration: underline; }
    .jobs-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin: 18px 24px 0; }
    .jobs-summary { color: var(--mod-muted); font-size: .82rem; }
    .jobs-summary strong { color: var(--mod-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .jobs-search { display: flex; width: min(100%, 390px); }
    .jobs-search input { min-width: 0; flex: 1; border: 1px solid var(--mod-line); border-right: 0; border-radius: 9px 0 0 9px; padding: 11px 13px; color: var(--mod-ink); background: #fff; outline: 0; font: inherit; font-size: .82rem; }
    .jobs-search input:focus { border-color: var(--mod-green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .jobs-search button { display: inline-flex; align-items: center; gap: 7px; border: 1px solid var(--mod-green); border-radius: 0 9px 9px 0; padding: 0 15px; color: #fff; background: var(--mod-green); cursor: pointer; font: 700 .78rem 'DM Sans', sans-serif; }
    .clear-search { color: var(--mod-muted); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .jobs-table-wrap { overflow-x: auto; }
    .jobs-table { min-width: 800px; margin: 0; }
    .jobs-table thead th { padding: 14px 24px; border: 0; background: #f7faf8; color: var(--mod-muted); font-size: .7rem; letter-spacing: .08em; text-transform: uppercase; }
    .jobs-table tbody td { padding: 18px 24px; border-color: #edf2f0; color: var(--mod-muted); font-size: .86rem; }
    .job-title { color: var(--mod-ink); font-weight: 700; }
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700; }
    .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-pending { color: #ad7111; background: #fff5d9; }
    .status-approved { color: #087f67; background: #e6f7f0; }
    .status-rejected { color: #c64c3d; background: #fff0ed; }
    .action-link { color: var(--mod-green); font-size: .8rem; font-weight: 700; text-decoration: none; }
    .action-link:hover { text-decoration: underline; }
    .review-trigger { display: inline-grid; place-items: center; width: 34px; height: 34px; border: 1px solid var(--mod-line); border-radius: 8px; padding: 0; color: var(--mod-green); background: #fff; cursor: pointer; font-size: .95rem; }
    .review-trigger:hover { color: #fff; border-color: var(--mod-green); background: var(--mod-green); }
    .review-modal { position: fixed; inset: 0; z-index: 1050; display: none; place-items: center; padding: 20px; background: rgba(24,35,45,.48); }
    .review-modal.is-open { display: grid; }
    .review-dialog { width: min(100%, 650px); max-height: min(720px, 90vh); overflow-y: auto; border-radius: 14px; background: #fff; box-shadow: 0 20px 60px rgba(24,35,45,.2); }
    .review-dialog-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 18px; padding: 24px; border-bottom: 1px solid var(--mod-line); }
    .review-dialog-header h2 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.35rem; }
    .review-dialog-header p { margin: 5px 0 0; color: var(--mod-muted); font-size: .8rem; }
    .review-close { border: 0; color: var(--mod-muted); background: transparent; font-size: 1.25rem; cursor: pointer; }
    .review-dialog-body { padding: 24px; }
    .review-detail { margin-bottom: 18px; }
    .review-detail-label { display: block; margin-bottom: 5px; color: var(--mod-muted); font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .review-detail-value { color: var(--mod-ink); white-space: pre-line; }
    .review-detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 24px; }
    .review-dialog-actions { position: relative; z-index: 1; display: flex; justify-content: flex-end; gap: 10px; padding: 18px 24px; border-top: 1px solid var(--mod-line); background: #fff; }
    .action-btn { appearance: none; display: inline-flex; align-items: center; justify-content: center; min-width: 92px; min-height: 40px; border: 0; border-radius: 8px; padding: 10px 16px; color: #fff !important; font: 700 .78rem 'DM Sans', sans-serif; line-height: 1; cursor: pointer; opacity: 1; visibility: visible; transition: background .2s, transform .2s; }
    .action-btn:hover { transform: translateY(-1px); }
    .review-approve-btn { display: inline-flex !important; width: 96px; background: #087f67 !important; color: #fff !important; }
    .review-approve-btn:hover { background: #05634f !important; }
    .review-reject-btn { display: inline-flex !important; width: 96px; background: #c64c3d !important; color: #fff !important; }
    .review-reject-btn:hover { background: #a33b30 !important; }
    .review-dialog-actions .action-btn:disabled { opacity: .55; cursor: wait; }
    .moderation-notice { position: fixed; top: 22px; right: 22px; z-index: 1100; display: flex; align-items: center; gap: 9px; width: min(360px, calc(100vw - 44px)); padding: 14px 17px; border: 1px solid #b9e7d5; border-radius: 10px; color: #087f67; background: #eaf8f2; box-shadow: 0 12px 30px rgba(24,35,45,.16); font-size: .84rem; font-weight: 700; opacity: 0; transform: translateX(calc(100% + 22px)); pointer-events: none; transition: opacity .25s ease, transform .35s cubic-bezier(.22,1,.36,1); }
    .moderation-notice.is-visible { opacity: 1; transform: translateX(0); }
    @media (max-width: 620px) { .moderation-notice { top: 14px; right: 14px; width: calc(100vw - 28px); } .review-modal { align-items: end; padding: 0; } .review-dialog { width: 100%; max-height: 92vh; border-radius: 14px 14px 0 0; } .review-dialog-header, .review-dialog-body { padding: 20px; } .review-detail-grid { grid-template-columns: 1fr; gap: 0; } .review-dialog-actions { padding: 16px 20px 20px; } .action-btn { flex: 1; } }
    .empty-state { padding: 60px 24px; text-align: center; }
    .empty-icon { display: grid; place-items: center; width: 52px; height: 52px; margin: 0 auto 14px; border-radius: 14px; color: var(--mod-green); background: var(--mod-mint); }
    .empty-state h3 { margin: 0 0 8px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.15rem; }
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
    @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 620px) { .moderator-dashboard { padding: 28px 0 44px; } .dashboard-hero { align-items: flex-start; flex-direction: column; } .dashboard-action { width: 100%; justify-content: center; } .stats-grid { grid-template-columns: 1fr; } .section-heading { padding: 18px; } .jobs-toolbar { align-items: stretch; flex-direction: column; margin-right: 18px; margin-left: 18px; } .jobs-search { width: 100%; } .pagination-wrap { align-items: flex-start; flex-direction: column; padding-right: 17px; padding-left: 17px; } .pagination-nav { flex-wrap: wrap; } }
</style>
@endpush

@section('content')
<div class="container moderator-dashboard">
    <div class="dashboard-hero">
        <div>
            <span class="dashboard-kicker">Moderator workspace</span>
            <h1>Review and moderate jobs</h1>
            <p>Keep posted roles compliant, visible, and ready for candidates.</p>
        </div>
        <a href="{{ route('moderator.jobs') }}" class="dashboard-action"><i class="bi bi-list-check" aria-hidden="true"></i> Review pending jobs</a>
    </div>

    <div class="stats-grid">
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-icon"><i class="bi bi-list-check" aria-hidden="true"></i></span>
                <span class="metric-label">Pending jobs</span>
            </div>
            <strong class="metric-value">{{ $stats['pending_jobs'] ?? 0 }}</strong>
        </div>
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-icon"><i class="bi bi-check-circle" aria-hidden="true"></i></span>
                <span class="metric-label">Approved today</span>
            </div>
            <strong class="metric-value">{{ $stats['approved_today'] ?? 0 }}</strong>
        </div>
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-icon"><i class="bi bi-x-circle" aria-hidden="true"></i></span>
                <span class="metric-label">Rejected today</span>
            </div>
            <strong class="metric-value">{{ $stats['rejected_today'] ?? 0 }}</strong>
        </div>
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-icon"><i class="bi bi-briefcase" aria-hidden="true"></i></span>
                <span class="metric-label">Total jobs</span>
            </div>
            <strong class="metric-value">{{ $stats['total_jobs'] ?? 0 }}</strong>
        </div>
    </div>

    <section class="jobs-section" aria-labelledby="pending-jobs-heading">
        <div class="section-heading">
            <h2 id="pending-jobs-heading">Pending review</h2>
            <a href="{{ route('moderator.jobs') }}">Open queue <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>

        <div class="jobs-toolbar">
            <div class="jobs-summary" data-results-summary><strong>{{ $pendingJobs->total() }}</strong> {{ $pendingJobs->total() === 1 ? 'job' : 'jobs' }}{{ !empty($search) ? ' matching your search' : ' awaiting review' }}</div>
            <form class="jobs-search" method="GET" action="{{ route('moderator.dashboard') }}">
                <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Search pending jobs" aria-label="Search pending jobs">
                <button type="submit"><i class="bi bi-search" aria-hidden="true"></i> Search</button>
            </form>
            @if(!empty($search))<a class="clear-search" href="{{ route('moderator.dashboard') }}">Clear search</a>@endif
        </div>
        <div id="dashboard-results" class="jobs-results">@include('moderator._dashboard_results')</div>
    </section>
</div>
<div id="moderation-notice" class="moderation-notice" role="status"><i class="bi bi-check-circle" aria-hidden="true"></i><span></span></div>
<div id="review-modal" class="review-modal" role="dialog" aria-modal="true" aria-labelledby="review-modal-title">
    <div class="review-dialog">
        <div class="review-dialog-header">
            <div><h2 id="review-modal-title">Review job</h2><p id="review-modal-company"></p></div>
            <button type="button" class="review-close" aria-label="Close review dialog"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
        </div>
        <div class="review-dialog-body">
            <div class="review-detail-grid">
                <div class="review-detail"><span class="review-detail-label">Location</span><div id="review-modal-location" class="review-detail-value"></div></div>
                <div class="review-detail"><span class="review-detail-label">Category</span><div id="review-modal-category" class="review-detail-value"></div></div>
                <div class="review-detail"><span class="review-detail-label">Job type</span><div id="review-modal-type" class="review-detail-value"></div></div>
                <div class="review-detail"><span class="review-detail-label">Salary</span><div id="review-modal-salary" class="review-detail-value"></div></div>
                <div class="review-detail"><span class="review-detail-label">Deadline</span><div id="review-modal-deadline" class="review-detail-value"></div></div>
                <div class="review-detail"><span class="review-detail-label">Posted</span><div id="review-modal-posted" class="review-detail-value"></div></div>
                <div class="review-detail"><span class="review-detail-label">Status</span><div id="review-modal-status" class="review-detail-value"></div></div>
                <div class="review-detail"><span class="review-detail-label">Applications</span><div id="review-modal-applications" class="review-detail-value"></div></div>
            </div>
            <div class="review-detail"><span class="review-detail-label">Description</span><div id="review-modal-description" class="review-detail-value"></div></div>
            <div class="review-detail"><span class="review-detail-label">Requirements</span><div id="review-modal-requirements" class="review-detail-value"></div></div>
        </div>
        <div class="review-dialog-actions">
            <button type="button" class="action-btn review-reject-btn" data-review-status="rejected">Reject</button>
            <button type="button" class="action-btn review-approve-btn" data-review-status="approved">Approve</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const results = document.getElementById('dashboard-results');
    const searchForm = document.querySelector('.moderator-dashboard .jobs-search');
    const modal = document.getElementById('review-modal');
    const notice = document.getElementById('moderation-notice');
    let activeJob = null;
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
    document.addEventListener('click', async (event) => {
        const trigger = event.target.closest('.review-trigger');
        if (trigger) {
            activeJob = {
                id: trigger.dataset.jobId,
                title: trigger.dataset.jobTitle,
                company: trigger.dataset.jobCompany,
                location: trigger.dataset.jobLocation,
                category: trigger.dataset.jobCategory,
                type: trigger.dataset.jobType,
                salary: trigger.dataset.jobSalaryMin && trigger.dataset.jobSalaryMax
                    ? `${trigger.dataset.jobSalaryMin} - ${trigger.dataset.jobSalaryMax}`
                    : trigger.dataset.jobSalaryMin || trigger.dataset.jobSalaryMax || '',
                deadline: trigger.dataset.jobDeadline,
                posted: trigger.dataset.jobPosted,
                status: trigger.dataset.jobStatus,
                applications: trigger.dataset.jobApplications,
                description: trigger.dataset.jobDescription,
                requirements: trigger.dataset.jobRequirements,
            };
            document.getElementById('review-modal-title').textContent = activeJob.title;
            document.getElementById('review-modal-company').textContent = activeJob.company;
            document.getElementById('review-modal-location').textContent = activeJob.location || 'Not specified';
            document.getElementById('review-modal-category').textContent = activeJob.category || 'Not specified';
            document.getElementById('review-modal-type').textContent = activeJob.type || 'Not specified';
            document.getElementById('review-modal-salary').textContent = activeJob.salary || 'Not specified';
            document.getElementById('review-modal-deadline').textContent = activeJob.deadline || 'Not specified';
            document.getElementById('review-modal-posted').textContent = activeJob.posted || 'Not specified';
            document.getElementById('review-modal-status').textContent = activeJob.status || 'Not specified';
            document.getElementById('review-modal-applications').textContent = activeJob.applications || '0';
            document.getElementById('review-modal-description').textContent = activeJob.description || 'Not specified';
            document.getElementById('review-modal-requirements').textContent = activeJob.requirements || 'Not specified';
            modal.classList.add('is-open');
            return;
        }
        if (event.target.closest('.review-close') || event.target === modal) { modal.classList.remove('is-open'); return; }
        const statusButton = event.target.closest('[data-review-status]');
        if (!statusButton || !activeJob) return;
        document.querySelectorAll('[data-review-status]').forEach((button) => button.disabled = true);
        const action = statusButton.dataset.reviewStatus === 'approved' ? 'approve' : 'reject';
        try {
            const response = await fetch(`/moderator/jobs/${activeJob.id}/${action}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Unable to update job');
            const payload = await response.json();
            modal.classList.remove('is-open');
            notice.querySelector('span').textContent = payload.message || 'Job status updated successfully.';
            notice.classList.add('is-visible');
            window.setTimeout(() => notice.classList.remove('is-visible'), 4500);
            loadResults(window.location.href, false);
        } catch (error) { window.location.reload(); }
        finally { document.querySelectorAll('[data-review-status]').forEach((button) => button.disabled = false); }
    });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') modal.classList.remove('is-open'); });
    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = new URL(searchForm.action, window.location.origin);
        const search = searchForm.querySelector('[name="search"]').value.trim();
        if (search) url.searchParams.set('search', search);
        const perPage = results.querySelector('#dashboard_per_page')?.value;
        if (perPage) url.searchParams.set('per_page', perPage);
        loadResults(url.toString());
    });
    results.addEventListener('change', (event) => {
        if (event.target.id !== 'dashboard_per_page') return;
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