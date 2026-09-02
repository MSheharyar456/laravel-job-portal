@if(isset($jobs) && count($jobs) > 0)
    <p class="text-muted mb-3">Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }} jobs</p>

    <div class="row g-4">
        @foreach($jobs as $job)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2">{{ $job->title }}</h5>
                            <p class="text-muted mb-2"><i class="bi bi-building"></i> {{ $job->employer->user->name ?? 'Company Name' }}</p>
                        </div>
                        <span class="badge badge-purple">{{ ucfirst($job->job_type) }}</span>
                    </div>
                    <p class="text-muted mb-3"><i class="bi bi-geo-alt-fill"></i> {{ $job->location }} &nbsp;&nbsp;
                        @if($job->salary_min && $job->salary_max)<i class="bi bi-cash"></i> ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}@endif
                    </p>
                    <p class="mb-3">{{ Str::limit($job->description, 120) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted"><i class="bi bi-clock"></i> Posted {{ $job->created_at->diffForHumans() }}</small>
                        <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm">View Details <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $jobs->links() }}</div>
@else
    <div class="card text-center p-5">
        <i class="bi bi-inbox" style="font-size: 4rem; color: var(--gray-text);"></i>
        <h4 class="mt-3">No jobs found</h4>
        <p class="text-muted">Try adjusting your search filters</p>
    </div>
@endif
