<span data-results-summary hidden><strong>{{ $pendingJobs->total() }}</strong> {{ $pendingJobs->total() === 1 ? 'job' : 'jobs' }}{{ !empty($search) ? ' matching your search' : ' awaiting review' }}</span>
@if($pendingJobs->count() > 0)
    <div class="jobs-table-wrap">
        <table class="table jobs-table">
            <thead>
                <tr>
                    <th>Job title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingJobs as $job)
                    <tr>
                        <td class="job-title">{{ $job->title }}</td>
                        <td>{{ $job->employer && $job->employer->user ? $job->employer->user->name : 'Unknown employer' }}</td>
                        <td>{{ $job->location }}</td>
                        <td><span class="status-pill status-pending">Pending</span></td>
                        <td><button type="button" class="action-link review-trigger" title="View full job details" aria-label="View full details for {{ $job->title }}" data-job-id="{{ $job->id }}" data-job-title="{{ $job->title }}" data-job-company="{{ $job->employer && $job->employer->user ? $job->employer->user->name : 'Unknown employer' }}" data-job-location="{{ $job->location }}" data-job-category="{{ $job->category->name ?? 'Not specified' }}" data-job-type="{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}" data-job-salary-min="{{ $job->salary_min ?? '' }}" data-job-salary-max="{{ $job->salary_max ?? '' }}" data-job-deadline="{{ $job->deadline ? $job->deadline->format('M d, Y') : '' }}" data-job-posted="{{ $job->created_at ? $job->created_at->format('M d, Y') : '' }}" data-job-status="{{ ucfirst($job->status) }}" data-job-applications="{{ $job->applications_count }}" data-job-description="{{ $job->description }}" data-job-requirements="{{ $job->requirements ?? '' }}"><i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden">View details</span></button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">
        <form class="per-page-form" method="GET" action="{{ route('moderator.dashboard') }}">
            @if(!empty($search))<input type="hidden" name="search" value="{{ $search }}">@endif
            <label for="dashboard_per_page">Show</label>
            <select id="dashboard_per_page" name="per_page" aria-label="Pending jobs per page">
                @foreach([5, 10, 20, 50] as $option)<option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>@endforeach
            </select>
            <span>per page</span>
        </form>
        @if($pendingJobs->hasPages())
            <span class="pagination-info">Showing {{ $pendingJobs->firstItem() }} to {{ $pendingJobs->lastItem() }} of {{ $pendingJobs->total() }}</span>
            <nav class="pagination-nav" aria-label="Pending jobs pagination">
                <a class="pagination-link {{ $pendingJobs->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $pendingJobs->previousPageUrl() ?? '#' }}" aria-label="Previous page">Previous</a>
                @for($page = 1; $page <= $pendingJobs->lastPage(); $page++)
                    <a class="pagination-link {{ $pendingJobs->currentPage() === $page ? 'is-current' : '' }}" href="{{ $pendingJobs->url($page) }}">{{ $page }}</a>
                @endfor
                <a class="pagination-link {{ $pendingJobs->currentPage() === $pendingJobs->lastPage() ? 'is-disabled' : '' }}" href="{{ $pendingJobs->nextPageUrl() ?? '#' }}" aria-label="Next page">Next</a>
            </nav>
        @endif
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-briefcase" aria-hidden="true"></i></div>
        <h3>No jobs waiting for moderation</h3>
        <p>{{ !empty($search) ? 'No pending jobs match your search.' : 'The review queue is clear right now.' }}</p>
    </div>
@endif
