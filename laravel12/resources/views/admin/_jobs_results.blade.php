<span data-jobs-summary hidden><strong>{{ $jobs->total() }}</strong> {{ $jobs->total() === 1 ? 'job' : 'jobs' }}{{ !empty($search) ? ' matching your search' : '' }}</span>
@if($jobs->count() > 0)
    <div class="table-responsive"><table class="table admin-jobs-table"><thead><tr><th>Job</th><th>Company</th><th>Category</th><th>Status</th><th>Posted</th><th>Actions</th></tr></thead><tbody>
        @foreach($jobs as $job)
            <tr>
                <td><span class="job-title">{{ $job->title }}</span><span class="job-meta"><i class="bi bi-geo-alt-fill"></i>{{ $job->location }}</span></td>
                <td>{{ $job->employer->user->name ?? 'Unknown employer' }}</td>
                <td>{{ $job->category->name ?? 'Uncategorized' }}</td>
                <td><span class="job-status job-status-{{ $job->status == 'approved' ? 'approved' : ($job->status == 'rejected' ? 'rejected' : 'pending') }}">{{ ucfirst($job->status) }}</span></td>
                <td>{{ $job->created_at->diffForHumans() }}</td>
                <td><div class="job-controls">
                    <button type="button" class="job-btn view-job-btn" title="View full job details" aria-label="View full details for {{ $job->title }}" data-job-title="{{ $job->title }}" data-job-company="{{ $job->employer->user->name ?? 'Unknown employer' }}" data-job-location="{{ $job->location }}" data-job-category="{{ $job->category->name ?? 'Uncategorized' }}" data-job-type="{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}" data-job-salary-min="{{ $job->salary_min ?? '' }}" data-job-salary-max="{{ $job->salary_max ?? '' }}" data-job-deadline="{{ $job->deadline ? $job->deadline->format('M d, Y') : '' }}" data-job-posted="{{ $job->created_at ? $job->created_at->format('M d, Y') : '' }}" data-job-status="{{ ucfirst($job->status) }}" data-job-applications="{{ $job->applications_count }}" data-job-description="{{ $job->description }}" data-job-requirements="{{ $job->requirements ?? '' }}"><i class="bi bi-eye" aria-hidden="true"></i><span class="visually-hidden">View details</span></button>
                    @if($job->status !== 'approved')<form method="POST" action="{{ route('admin.jobs.status', $job->id) }}" class="job-action-form">@csrf @method('PUT')<input type="hidden" name="status" value="approved"><button class="job-btn approve-btn" type="submit">Approve</button></form>@endif
                    @if($job->status !== 'rejected')<form method="POST" action="{{ route('admin.jobs.status', $job->id) }}" class="job-action-form">@csrf @method('PUT')<input type="hidden" name="status" value="rejected"><button class="job-btn reject-btn" type="submit">Reject</button></form>@endif
                    <form method="POST" action="{{ route('admin.jobs.destroy', $job->id) }}" class="job-action-form" onsubmit="return confirm('Delete this job?');">@csrf @method('DELETE')<button class="job-btn delete-btn" type="submit">Delete</button></form>
                </div></td>
            </tr>
        @endforeach
    </tbody></table></div>
    <div class="jobs-pagination">
        <form class="per-page-form" method="GET" action="{{ route('admin.jobs') }}">
            @if(!empty($search))<input type="hidden" name="search" value="{{ $search }}">@endif
            @if(!empty($status))<input type="hidden" name="status" value="{{ $status }}">@endif
            <label for="jobs_per_page">Show</label><select id="jobs_per_page" name="per_page" aria-label="Jobs per page">@foreach([5, 10, 20, 50] as $option)<option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>@endforeach</select><span>per page</span>
        </form>
        @if($jobs->hasPages())<span class="pagination-info">Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }}</span><nav class="pagination-nav" aria-label="Jobs pagination"><a class="pagination-link {{ $jobs->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $jobs->previousPageUrl() ?? '#' }}">Previous</a>@for($page = 1; $page <= $jobs->lastPage(); $page++)<a class="pagination-link {{ $jobs->currentPage() === $page ? 'is-current' : '' }}" href="{{ $jobs->url($page) }}">{{ $page }}</a>@endfor<a class="pagination-link {{ $jobs->currentPage() === $jobs->lastPage() ? 'is-disabled' : '' }}" href="{{ $jobs->nextPageUrl() ?? '#' }}">Next</a></nav>@endif
    </div>
@else
    <div class="jobs-empty"><i class="bi bi-briefcase"></i><h2>No jobs found</h2><p>{{ !empty($search) || !empty($status) ? 'Try changing your search or status filter.' : 'Posted jobs will appear here.' }}</p></div>
@endif
