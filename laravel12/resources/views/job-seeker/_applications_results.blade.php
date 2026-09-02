<span data-applications-summary hidden><strong>{{ $applications->total() }}</strong> {{ $applications->total() === 1 ? 'application' : 'applications' }}{{ !empty($search) ? ' matching your search' : '' }}</span>
@if($applications->count() > 0)
    <div class="applications-card">
        <div class="table-responsive">
            <table class="table applications-table">
                <thead><tr><th>Job title</th><th>Company</th><th>Applied date</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td><a class="application-job" href="{{ route('jobs.show', $app->job_id) }}">{{ $app->job->title }}</a></td>
                        <td><span class="application-company">{{ $app->job->employer->user->name ?? 'N/A' }}</span><span class="application-location"><i class="bi bi-geo-alt-fill"></i>{{ $app->job->location }}</span></td>
                        <td>{{ $app->created_at->format('M d, Y') }}</td>
                        <td><span class="application-status application-{{ $app->status == 'accepted' ? 'accepted' : ($app->status == 'rejected' ? 'rejected' : 'pending') }}">{{ ucfirst($app->status) }}</span></td>
                        <td><a href="{{ route('jobs.show', $app->job_id) }}" class="btn btn-sm btn-outline-primary application-action">View job <i class="bi bi-arrow-right"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-area">
            <form class="per-page-form" method="GET" action="{{ route('job-seeker.applications') }}">
                @if(!empty($search))<input type="hidden" name="search" value="{{ $search }}">@endif
                <label for="applications_per_page">Show</label>
                <select id="applications_per_page" name="per_page" aria-label="Applications per page">
                    @foreach([5, 10, 20, 50] as $option)<option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>@endforeach
                </select>
                <span>per page</span>
            </form>
            @if($applications->hasPages())
                <span class="pagination-info">Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }}</span>
                <nav class="pagination-nav" aria-label="Applications pagination">
                    <a class="pagination-link {{ $applications->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $applications->previousPageUrl() ?? '#' }}">Previous</a>
                    @for($page = 1; $page <= $applications->lastPage(); $page++)<a class="pagination-link {{ $applications->currentPage() === $page ? 'is-current' : '' }}" href="{{ $applications->url($page) }}">{{ $page }}</a>@endfor
                    <a class="pagination-link {{ $applications->currentPage() === $applications->lastPage() ? 'is-disabled' : '' }}" href="{{ $applications->nextPageUrl() ?? '#' }}">Next</a>
                </nav>
            @endif
        </div>
    </div>
@else
    <div class="applications-card applications-empty"><i class="bi bi-search"></i><h2>No applications found</h2><p>{{ !empty($search) ? 'Try another job title, company, or location.' : 'Find a role that fits your goals and start your next opportunity.' }}</p><a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse jobs <i class="bi bi-arrow-right"></i></a></div>
@endif
