@if(isset($jobs) && count($jobs) > 0)
    <section class="jobs-table-card" aria-label="Posted jobs" data-jobs-total="{{ $jobs->total() }}">
        <div class="jobs-table-wrap">
            <table class="table jobs-table">
                <thead><tr><th>Job title</th><th>Category</th><th>Type</th><th>Status</th><th>Applications</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($jobs as $job)
                    <tr>
                        <td><span class="job-name">{{ $job->title }}</span><span class="job-meta"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i>{{ $job->location }}</span></td>
                        <td>{{ $job->category->name ?? 'N/A' }}</td>
                        <td><span class="type-label"><i class="bi bi-clock" aria-hidden="true"></i>{{ str_replace('-', ' ', $job->job_type) }}</span></td>
                        <td><span class="status-pill status-{{ $job->status == 'approved' ? 'approved' : ($job->status == 'pending' ? 'pending' : 'rejected') }}">{{ ucfirst($job->status) }}</span></td>
                        <td>{{ $job->applications_count }}</td>
                        <td>
                            <a class="table-action" href="{{ route('employer.jobs.edit', $job->id) }}"><i class="bi bi-pencil" aria-hidden="true"></i> Edit</a><a class="table-action" href="{{ route('employer.jobs.applications', $job->id) }}"><i class="bi bi-people" aria-hidden="true"></i> Applications</a>
                            @if(in_array($job->status, ['pending', 'rejected'], true))
                                <button class="table-action table-delete-action" type="button" data-delete-url="{{ route('employer.jobs.destroy', $job->id) }}" data-job-title="{{ $job->title }}"><i class="bi bi-trash3" aria-hidden="true"></i> Delete</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">
            <form class="per-page-form" method="GET" action="{{ route('employer.jobs') }}">
                @if(!empty($search))<input type="hidden" name="search" value="{{ $search }}">@endif
                <label for="per_page">Show</label>
                <select id="per_page" name="per_page" aria-label="Jobs per page">
                    @foreach([5, 10, 20, 50] as $option)<option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>@endforeach
                </select>
                <span>per page</span>
            </form>
            @if($jobs->hasPages())
                <span class="pagination-info">Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }}</span>
                <nav class="pagination-nav" aria-label="Jobs pagination">
                    <a class="pagination-link {{ $jobs->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $jobs->previousPageUrl() ?? '#' }}" aria-label="Previous page">Previous</a>
                    @for($page = 1; $page <= $jobs->lastPage(); $page++)
                        <a class="pagination-link {{ $jobs->currentPage() === $page ? 'is-current' : '' }}" href="{{ $jobs->url($page) }}" aria-current="{{ $jobs->currentPage() === $page ? 'page' : 'false' }}">{{ $page }}</a>
                    @endfor
                    <a class="pagination-link {{ $jobs->currentPage() === $jobs->lastPage() ? 'is-disabled' : '' }}" href="{{ $jobs->nextPageUrl() ?? '#' }}" aria-label="Next page">Next</a>
                </nav>
            @endif
        </div>
    </section>
@else
    <section class="jobs-table-card empty-state" data-jobs-total="0"><div class="empty-icon"><i class="bi bi-briefcase" aria-hidden="true"></i></div><h2>No jobs posted yet</h2><p>{{ !empty($search) ? 'No jobs match your search. Try another keyword.' : 'Create your first role and start building your candidate pipeline.' }}</p><a href="{{ route('employer.jobs.create') }}" class="jobs-page-action"><i class="bi bi-plus-lg" aria-hidden="true"></i> Post a new job</a></section>
@endif
