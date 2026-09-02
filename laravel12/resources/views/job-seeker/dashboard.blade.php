@extends('layouts.app')

@section('title', 'Job Seeker Dashboard')

@push('styles')
<style>
    .seeker-dashboard { --seeker-ink: #18232d; --seeker-muted: #6d7a80; --seeker-line: #dce5e4; --seeker-teal: #087f67; --seeker-mint: #ccefe3; padding: 18px 0 54px; color: var(--seeker-ink); }
    .seeker-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 28px; }
    .seeker-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 9px; color: var(--seeker-teal); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .seeker-kicker::before { width: 22px; height: 2px; background: #f27864; content: ''; }
    .seeker-heading h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .seeker-heading p { margin: 7px 0 0; color: var(--seeker-muted); }
    .seeker-dashboard .stat-card { min-height: 116px; border: 1px solid var(--seeker-line); border-left: 0 !important; border-radius: 12px; box-shadow: none; }
    .seeker-dashboard .stat-card h3 { color: var(--seeker-teal); font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .seeker-dashboard .stat-card p { font-size: .8rem; }
    .seeker-dashboard .card { border: 1px solid var(--seeker-line); border-radius: 12px; box-shadow: none; transition: none; }
    .seeker-dashboard .card:hover { box-shadow: none; transform: none; }
    .seeker-dashboard .card-header { border-bottom: 1px solid var(--seeker-line); border-radius: 12px 12px 0 0 !important; color: var(--seeker-ink); background: #f7faf8; font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .seeker-dashboard .table { margin-bottom: 18px; }
    .seeker-dashboard .table thead th { color: var(--seeker-muted); background: #f7faf8; font-size: .7rem; letter-spacing: .06em; text-transform: uppercase; }
    .seeker-dashboard .table tbody td { color: var(--seeker-muted); font-size: .84rem; }
    .seeker-dashboard .table a { color: var(--seeker-teal); text-decoration: none; }
    .seeker-dashboard .table a:hover { text-decoration: underline; }
    .seeker-dashboard .badge { border-radius: 999px; padding: 7px 10px; font-size: .7rem; }
    .seeker-dashboard .btn-primary { border: 0; border-radius: 8px; background: var(--seeker-teal); font-weight: 700; }
    .seeker-dashboard .btn-primary:hover { background: #05634f; }
    .seeker-dashboard .btn-outline-primary { border-color: var(--seeker-teal); color: var(--seeker-teal); border-radius: 8px; }
    .seeker-dashboard .btn-outline-primary:hover { border-color: var(--seeker-teal); color: #fff; background: var(--seeker-teal); transform: none; }
    .seeker-dashboard .progress { height: 10px !important; overflow: hidden; border-radius: 999px; background: #edf2f0; }
    .seeker-dashboard .progress-bar { border-radius: inherit; background: var(--seeker-teal) !important; font-size: 0; }
    .seeker-side-title { font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    @media (max-width: 767px) { .seeker-dashboard { padding: 8px 0 42px; } .seeker-heading { align-items: flex-start; flex-direction: column; } .seeker-dashboard .table { min-width: 620px; } }
</style>
@endpush

@section('content')
<div class="my-4 seeker-dashboard">
    <div class="seeker-heading">
        <div><span class="seeker-kicker">Job seeker workspace</span><h1>Welcome, {{ auth()->user()->name }}!</h1><p>Track your applications and keep your profile ready for the next opportunity.</p></div>
    </div>
    
    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <h3>{{ $stats['total_applications'] ?? 0 }}</h3>
                <p>Total Applications</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid var(--warning);">
                <h3>{{ $stats['pending'] ?? 0 }}</h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid var(--success);">
                <h3>{{ $stats['accepted'] ?? 0 }}</h3>
                <p>Accepted</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid var(--danger);">
                <h3>{{ $stats['rejected'] ?? 0 }}</h3>
                <p>Rejected</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Recent Applications -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Recent Applications</h5>
                </div>
                <div class="card-body">
                    @if(isset($recent_applications) && count($recent_applications) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Company</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_applications as $application)
                                    <tr>
                                        <td>
                                            <a href="{{ route('jobs.show', $application->job_id) }}" class="fw-bold">
                                                {{ $application->job->title }}
                                            </a>
                                        </td>
                                        <td>{{ $application->job->employer->user->name ?? 'N/A' }}</td>
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($application->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($application->status == 'accepted')
                                                <span class="badge badge-success">Accepted</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('job-seeker.applications') }}" class="btn btn-outline-primary btn-sm">
                            View All Applications <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <p class="text-muted text-center py-4">No applications yet. <a href="{{ route('jobs.index') }}">Browse jobs</a> to get started!</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Profile Completion -->
            <div class="card mb-4">
                    <div class="card-body">
                <h5 class="fw-bold mb-3 seeker-side-title">Profile completion</h5>
                    @php
                        $profile = auth()->user()->jobSeekerProfile;
                        $completion = 0;
                        if($profile) {
                            if($profile->resume_path) $completion += 25;
                            if($profile->skills) $completion += 25;
                            if($profile->experience) $completion += 25;
                            if($profile->education) $completion += 25;
                        }
                    @endphp
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $completion }}%; background: var(--primary-purple);">
                            {{ $completion }}%
                        </div>
                    </div>
                    @if($completion < 100)
                        <p class="text-muted small">Complete your profile to increase your chances!</p>
                        <a href="{{ route('job-seeker.profile') }}" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-pencil"></i> Complete Profile
                        </a>
                    @else
                        <p class="text-success small"><i class="bi bi-check-circle-fill"></i> Profile completed!</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Quick Actions</h5>
                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-search"></i> Browse Jobs
                    </a>
                    <a href="{{ route('job-seeker.profile') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-person"></i> Edit Profile
                    </a>
                    <a href="{{ route('job-seeker.applications') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-file-text"></i> My Applications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
