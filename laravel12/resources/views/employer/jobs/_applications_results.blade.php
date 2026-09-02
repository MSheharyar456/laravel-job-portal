@if($applications->count() > 0)
    <section class="applications-card" aria-label="Job applications" data-applications-total="{{ $applications->total() }}">
        <div class="applications-table-wrap">
            <table class="table applications-table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Cover letter</th>
                        <th>Status</th>
                        <th>Resume</th>
                        <th>Applied</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                        <tr>
                            <td>
                                <span class="candidate-name">{{ $app->user->name ?? 'Unknown candidate' }}</span>
                                <span class="candidate-meta"><i class="bi bi-envelope" aria-hidden="true"></i> {{ $app->user->email ?? 'No email' }}</span>
                            </td>
                            <td>
                                @if(!empty($app->cover_letter))
                                    <span class="text-quiet">{{ Str::limit(strip_tags($app->cover_letter), 120) }}</span>
                                @else
                                    <span class="text-quiet">No cover letter provided</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('employer.applications.update', $app->id) }}" class="status-form">
                                    @csrf
                                    @method('PUT')
                                    <select class="status-select" name="status" aria-label="Update application status" onchange="this.form.submit()">
                                        @foreach(['pending', 'reviewed', 'shortlisted', 'accepted', 'rejected'] as $status)
                                            <option value="{{ $status }}" {{ $app->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if(!empty($app->resume_path))
                                    <a class="resume-link" href="{{ Storage::disk('public')->url($app->resume_path) }}" target="_blank" rel="noopener noreferrer">View resume</a>
                                @else
                                    <span class="text-quiet">No resume</span>
                                @endif
                            </td>
                            <td>{{ $app->created_at ? $app->created_at->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="applications-pagination">
            <form class="applications-per-page" method="GET" action="{{ url()->current() }}">
                @if(!empty($search))<input type="hidden" name="search" value="{{ $search }}">@endif
                <label for="applications_per_page">Show</label>
                <select id="applications_per_page" name="per_page" aria-label="Applications per page">
                    @foreach([5, 10, 20, 50] as $option)<option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>@endforeach
                </select>
                <span>per page</span>
            </form>
            @if($applications->hasPages())
                <span class="applications-pagination-info">Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }}</span>
                <nav class="applications-pagination-nav" aria-label="Applications pagination">
                    <a class="applications-pagination-link {{ $applications->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $applications->previousPageUrl() ?? '#' }}">Previous</a>
                    @for($page = 1; $page <= $applications->lastPage(); $page++)
                        <a class="applications-pagination-link {{ $applications->currentPage() === $page ? 'is-current' : '' }}" href="{{ $applications->url($page) }}">{{ $page }}</a>
                    @endfor
                    <a class="applications-pagination-link {{ $applications->currentPage() === $applications->lastPage() ? 'is-disabled' : '' }}" href="{{ $applications->nextPageUrl() ?? '#' }}">Next</a>
                </nav>
            @endif
        </div>
    </section>
@else
    <section class="applications-card empty-state" data-applications-total="0">
        <div class="empty-icon"><i class="bi bi-people" aria-hidden="true"></i></div>
        <h2>No applications found</h2>
        <p>{{ !empty($search) ? 'No applications match your search. Try another keyword.' : 'Once candidates apply for this role, their details will appear here.' }}</p>
    </section>
@endif
