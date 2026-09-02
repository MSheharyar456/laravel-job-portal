<span data-results-summary hidden><strong>{{ $jobs->total() }}</strong> {{ $jobs->total() === 1 ? 'job' : 'jobs' }}{{ !empty($search) ? ' matching your search' : ' in the review queue' }}</span>
@if($jobs->count() > 0)
    <div class="jobs-table-wrap">
        <table class="table jobs-table">
            <thead><tr><th>Job title</th><th>Company</th><th>Location</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @foreach($jobs as $job)
                <tr>
                    <td><span class="job-name">{{ $job->title }}</span><span class="job-meta">{{ Str::limit($job->description, 80) }}</span></td>
                    <td>{{ $job->employer && $job->employer->user ? $job->employer->user->name : 'Unknown employer' }}</td>
                    <td>{{ $job->location }}</td>
                    <td><span class="status-pill status-{{ $job->status == 'approved' ? 'approved' : ($job->status == 'rejected' ? 'rejected' : 'pending') }}">{{ ucfirst($job->status) }}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="action-btn view-job-btn" title="View full job details" aria-label="View full details for {{ $job->title }}" data-job-title="{{ $job->title }}" data-job-company="{{ $job->employer->user->name ?? 'Unknown employer' }}" data-job-location="{{ $job->location }}" data-job-category="{{ $job->category->name ?? 'Not specified' }}" data-job-type="{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}" data-job-salary-min="{{ $job->salary_min ?? '' }}" data-job-salary-max="{{ $job->salary_max ?? '' }}" data-job-deadline="{{ $job->deadline ? $job->deadline->format('M d, Y') : '' }}" data-job-posted="{{ $job->created_at ? $job->created_at->format('M d, Y') : '' }}" data-job-status="{{ ucfirst($job->status) }}" data-job-applications="{{ $job->applications_count }}" data-job-description="{{ $job->description }}" data-job-requirements="{{ $job->requirements ?? '' }}"><i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden">View details</span></button>
                            <form class="moderation-form" method="POST" action="{{ route('moderator.jobs.approve', $job->id) }}">@csrf<button type="submit" class="action-btn approve-btn">Approve</button></form>
                            <form class="moderation-form" method="POST" action="{{ route('moderator.jobs.reject', $job->id) }}">@csrf<button type="submit" class="action-btn reject-btn">Reject</button></form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">
        <form class="per-page-form" method="GET" action="{{ route('moderator.jobs') }}">
            @if(!empty($search))<input type="hidden" name="search" value="{{ $search }}">@endif
            @if(!empty($status))<input type="hidden" name="status" value="{{ $status }}">@endif
            <label for="jobs_per_page">Show</label>
            <select id="jobs_per_page" name="per_page" aria-label="Jobs per page">
                @foreach([5, 10, 20, 50] as $option)<option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>@endforeach
            </select>
            <span>per page</span>
        </form>
        @if($jobs->hasPages())
            <span class="pagination-info">Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }}</span>
            <nav class="pagination-nav" aria-label="Jobs pagination">
                <a class="pagination-link {{ $jobs->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $jobs->previousPageUrl() ?? '#' }}">Previous</a>
                @for($page = 1; $page <= $jobs->lastPage(); $page++)<a class="pagination-link {{ $jobs->currentPage() === $page ? 'is-current' : '' }}" href="{{ $jobs->url($page) }}">{{ $page }}</a>@endfor
                <a class="pagination-link {{ $jobs->currentPage() === $jobs->lastPage() ? 'is-disabled' : '' }}" href="{{ $jobs->nextPageUrl() ?? '#' }}">Next</a>
            </nav>
        @endif
    </div>
@else
    <div class="empty-state"><div class="empty-icon"><i class="bi bi-check-circle" aria-hidden="true"></i></div><h2>No jobs to review</h2><p>{{ !empty($search) ? 'No jobs match your search.' : 'The queue is clear at the moment.' }}</p></div>
@endif
