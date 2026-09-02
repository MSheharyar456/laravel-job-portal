@extends('layouts.app')

@section('title', $job->title . ' - Job Portal')

@push('styles')
<style>
    .job-detail-page { --detail-ink: #18232d; --detail-muted: #6d7a80; --detail-line: #dce5e4; --detail-teal: #087f67; --detail-mint: #ccefe3; max-width: 1120px; margin: 0 auto; padding: 18px 0 54px; color: var(--detail-ink); }
    .job-detail-page .card { border: 1px solid var(--detail-line); border-radius: 12px; box-shadow: none; transition: none; }
    .job-detail-page .card:hover { box-shadow: none; transform: none; }
    .job-detail-page .job-main-card .card-body { padding: clamp(24px, 4vw, 42px); }
    .job-detail-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; padding-bottom: 26px; border-bottom: 1px solid var(--detail-line); }
    .job-detail-heading h1 { margin: 0 0 9px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(1.8rem, 4vw, 2.8rem); letter-spacing: -.05em; }
    .job-company { margin: 0; color: var(--detail-muted); font-size: .9rem; }
    .job-company i, .detail-item i, .overview-item i { margin-right: 6px; color: var(--detail-teal); }
    .job-type-badge { flex-shrink: 0; border-radius: 999px; padding: 8px 12px; color: var(--detail-teal); background: var(--detail-mint); font-size: .74rem; font-weight: 700; white-space: nowrap; }
    .job-facts { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 15px; padding: 25px 0; }
    .detail-item { min-width: 0; color: var(--detail-muted); font-size: .84rem; }
    .detail-item strong { display: block; margin-top: 4px; padding-left: 24px; color: var(--detail-ink); font-size: .88rem; font-weight: 600; }
    .job-copy { padding-top: 25px; border-top: 1px solid var(--detail-line); }
    .job-copy + .job-copy { margin-top: 28px; }
    .job-copy h2, .overview-title { margin: 0 0 12px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.25rem; letter-spacing: -.03em; }
    .job-copy p { margin: 0; color: #40505a; line-height: 1.8; white-space: pre-wrap; }
    .job-side-card { position: sticky; top: 20px; }
    .job-side-card .card-body { padding: 25px; }
    .job-side-card .btn { border-radius: 8px; padding: 11px 14px; font-size: .82rem; font-weight: 700; }
    .job-side-card .btn-primary { border: 0; background: var(--detail-teal); }
    .job-side-card .btn-primary:hover { background: #05634f; }
    .job-side-card .btn-outline-primary { border-color: var(--detail-teal); color: var(--detail-teal); }
    .job-side-card .btn-outline-primary:hover { border-color: var(--detail-teal); color: #fff; background: var(--detail-teal); transform: none; }
    .overview-title { padding-top: 20px; border-top: 1px solid var(--detail-line); }
    .overview-list { display: grid; gap: 13px; margin: 0; }
    .overview-item { color: var(--detail-muted); font-size: .84rem; }
    .overview-item strong { color: var(--detail-ink); }
    @media (max-width: 767px) { .job-detail-page { padding: 8px 0 42px; } .job-detail-heading { flex-direction: column; gap: 14px; } .job-facts { grid-template-columns: 1fr; gap: 13px; } .job-side-card { position: static; } }
</style>
@endpush

@section('content')
<div class="my-4 job-detail-page">
    <div class="row">
        <div class="col-md-8">
            <div class="card job-main-card mb-4">
                <div class="card-body p-4">
                    <div class="job-detail-heading">
                        <div>
                            <h1>{{ $job->title }}</h1>
                            <p class="job-company">
                                <i class="bi bi-building"></i> {{ $job->employer->user->name ?? 'Company Name' }}
                            </p>
                        </div>
                        <span class="job-type-badge">{{ ucfirst($job->job_type) }}</span>
                    </div>

                    <div class="job-facts">
                        <div class="detail-item"><i class="bi bi-geo-alt-fill"></i>Location<strong>{{ $job->location }}</strong></div>
                        <div class="detail-item"><i class="bi bi-tag-fill"></i>Category<strong>{{ $job->category->name ?? 'N/A' }}</strong></div>
                        @if($job->salary_min && $job->salary_max)
                        <div class="detail-item"><i class="bi bi-cash"></i>Salary<strong>${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}</strong></div>
                        @endif
                        @if($job->deadline)
                        <div class="detail-item"><i class="bi bi-calendar-event"></i>Deadline<strong>{{ $job->deadline->format('M d, Y') }}</strong></div>
                        @endif
                        <div class="detail-item"><i class="bi bi-clock"></i>Posted<strong>{{ $job->created_at->diffForHumans() }}</strong></div>
                    </div>

                    <div class="job-copy">
                        <h2>Job description</h2>
                        <p>{{ $job->description }}</p>
                    </div>

                    @if($job->requirements)
                    <div class="job-copy">
                        <h2>Requirements</h2>
                        <p>{{ $job->requirements }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card job-side-card">
                <div class="card-body p-4">
                    @auth
                        @if(auth()->user()->isJobSeeker())
                            @if(isset($hasApplied) && $hasApplied)
                                <button class="btn btn-success w-100 mb-3" disabled>
                                    <i class="bi bi-check-circle-fill"></i> Applied
                                </button>
                                <p class="text-center text-muted small">You have already applied for this job</p>
                            @else
                                <a href="{{ route('job-seeker.jobs.apply', $job->id) }}" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-file-earmark-text"></i> Apply Now
                                </a>
                            @endif
                        @elseif(auth()->user()->isEmployer())
                            @if($job->employer && $job->employer->user_id == auth()->id())
                                <a href="{{ route('employer.jobs.edit', $job->id) }}" class="btn btn-outline-primary w-100 mb-3">
                                    <i class="bi bi-pencil"></i> Edit Job
                                </a>
                                <a href="{{ route('employer.jobs.applications', $job->id) }}" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-people"></i> View Applications
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Login to Apply
                        </a>
                        <p class="text-center text-muted small">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
                    @endauth

                    <hr>

                    <h2 class="overview-title">Job overview</h2>
                    <ul class="list-unstyled overview-list">
                        <li class="overview-item"><i class="bi bi-person-check"></i> <strong>Applications:</strong> {{ $job->applications_count }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
