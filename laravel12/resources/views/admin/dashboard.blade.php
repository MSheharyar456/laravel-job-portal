@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .admin-dashboard { --admin-ink: #18232d; --admin-muted: #6d7a80; --admin-line: #dce5e4; --admin-teal: #087f67; --admin-mint: #ccefe3; padding: 18px 0 54px; color: var(--admin-ink); }
    .admin-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 28px; }
    .admin-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 9px; color: var(--admin-teal); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .admin-kicker::before { width: 22px; height: 2px; background: #f27864; content: ''; }
    .admin-heading h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .admin-heading p { margin: 8px 0 0; color: var(--admin-muted); }
    .admin-actions { display: flex; gap: 9px; }
    .admin-action { display: inline-flex; align-items: center; gap: 8px; border-radius: 8px; padding: 11px 14px; color: #fff; background: var(--admin-teal); font-size: .78rem; font-weight: 700; text-decoration: none; }
    .admin-action:hover { color: #fff; background: #05634f; }
    .admin-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 30px; }
    .admin-stat { min-height: 125px; padding: 20px; border: 1px solid var(--admin-line); border-radius: 12px; background: #fff; }
    .admin-stat-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .admin-stat-icon { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 10px; color: var(--admin-teal); background: var(--admin-mint); }
    .admin-stat:nth-child(2) .admin-stat-icon { color: #2355d0; background: #eaf2ff; }
    .admin-stat:nth-child(3) .admin-stat-icon { color: #ad7111; background: #fff5d9; }
    .admin-stat:nth-child(4) .admin-stat-icon { color: #6d53d8; background: #f2ecff; }
    .admin-stat-label { color: var(--admin-muted); font-size: .76rem; font-weight: 700; }
    .admin-stat-value { display: block; margin-top: 15px; color: var(--admin-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 2.15rem; line-height: 1; letter-spacing: -.05em; }
    .admin-columns { display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(300px, .75fr); gap: 18px; }
    .admin-panel { overflow: hidden; border: 1px solid var(--admin-line); border-radius: 12px; background: #fff; }
    .admin-panel-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 19px 22px; border-bottom: 1px solid var(--admin-line); }
    .admin-panel-heading h2 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.1rem; }
    .admin-panel-heading a { color: var(--admin-teal); font-size: .76rem; font-weight: 700; text-decoration: none; }
    .admin-panel-heading a:hover { text-decoration: underline; }
    .admin-table-wrap { overflow-x: auto; }
    .admin-table { min-width: 580px; margin: 0; }
    .admin-table thead th { padding: 13px 22px; border: 0; background: #f7faf8; color: var(--admin-muted); font-size: .67rem; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap; }
    .admin-table tbody td { padding: 16px 22px; border-color: #edf2f0; color: var(--admin-muted); font-size: .8rem; }
    .admin-table tbody tr:last-child td { border-bottom: 0; }
    .admin-primary { color: var(--admin-ink); font-weight: 700; }
    .admin-secondary { display: block; margin-top: 3px; color: var(--admin-muted); font-size: .72rem; }
    .admin-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 9px; border-radius: 999px; font-size: .68rem; font-weight: 700; }
    .admin-status::before { width: 6px; height: 6px; border-radius: 50%; background: currentColor; content: ''; }
    .admin-status-approved { color: #087f67; background: #e6f7f0; }
    .admin-status-pending { color: #ad7111; background: #fff5d9; }
    .admin-status-rejected { color: #c64c3d; background: #fff0ed; }
    .admin-empty { padding: 42px 22px; color: var(--admin-muted); text-align: center; font-size: .82rem; }
    @media (max-width: 900px) { .admin-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } .admin-columns { grid-template-columns: 1fr; } }
    @media (max-width: 620px) { .admin-dashboard { padding: 8px 0 42px; } .admin-heading { align-items: flex-start; flex-direction: column; } .admin-actions { width: 100%; } .admin-action { flex: 1; justify-content: center; } .admin-stats { grid-template-columns: 1fr; } .admin-panel-heading, .admin-table thead th, .admin-table tbody td { padding-right: 17px; padding-left: 17px; } }
</style>
@endpush

@section('content')
<div class="container admin-dashboard">
    <div class="admin-heading">
        <div><span class="admin-kicker">Administration workspace</span><h1>Platform overview</h1><p>Monitor users, jobs, applications, and marketplace activity.</p></div>
        <div class="admin-actions"><a class="admin-action" href="{{ route('admin.users') }}"><i class="bi bi-people" aria-hidden="true"></i> Manage users</a><a class="admin-action" href="{{ route('admin.jobs') }}"><i class="bi bi-briefcase" aria-hidden="true"></i> Manage jobs</a></div>
    </div>
    <div class="admin-stats">
        <div class="admin-stat"><div class="admin-stat-top"><span class="admin-stat-label">Total users</span><span class="admin-stat-icon"><i class="bi bi-people" aria-hidden="true"></i></span></div><strong class="admin-stat-value">{{ $stats['total_users'] ?? 0 }}</strong></div>
        <div class="admin-stat"><div class="admin-stat-top"><span class="admin-stat-label">Total jobs</span><span class="admin-stat-icon"><i class="bi bi-briefcase" aria-hidden="true"></i></span></div><strong class="admin-stat-value">{{ $stats['total_jobs'] ?? 0 }}</strong></div>
        <div class="admin-stat"><div class="admin-stat-top"><span class="admin-stat-label">Pending approval</span><span class="admin-stat-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span></div><strong class="admin-stat-value">{{ $stats['pending_jobs'] ?? 0 }}</strong></div>
        <div class="admin-stat"><div class="admin-stat-top"><span class="admin-stat-label">Applications</span><span class="admin-stat-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span></div><strong class="admin-stat-value">{{ $stats['total_applications'] ?? 0 }}</strong></div>
    </div>
    <div class="admin-columns">
        <section class="admin-panel">
            <div class="admin-panel-heading"><h2>Recent jobs</h2><a href="{{ route('admin.jobs') }}">View all <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
            @if(isset($recentJobs) && $recentJobs->count() > 0)
                <div class="admin-table-wrap"><table class="table admin-table"><thead><tr><th>Job</th><th>Company</th><th>Status</th><th>Posted</th></tr></thead><tbody>
                    @foreach($recentJobs as $job)<tr><td><span class="admin-primary">{{ $job->title }}</span><span class="admin-secondary">{{ $job->location }}</span></td><td>{{ $job->employer->user->name ?? 'Unknown employer' }}</td><td><span class="admin-status admin-status-{{ $job->status == 'approved' ? 'approved' : ($job->status == 'rejected' ? 'rejected' : 'pending') }}">{{ ucfirst($job->status) }}</span></td><td>{{ $job->created_at->diffForHumans() }}</td></tr>@endforeach
                </tbody></table></div>
            @else<div class="admin-empty">No jobs have been posted yet.</div>@endif
        </section>
        <div class="admin-panel">
            <div class="admin-panel-heading"><h2>Recent users</h2><a href="{{ route('admin.users') }}">View all <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
            @if(isset($recentUsers) && $recentUsers->count() > 0)
                <div class="admin-table-wrap"><table class="table admin-table"><thead><tr><th>User</th><th>Role</th><th>Joined</th></tr></thead><tbody>
                    @foreach($recentUsers as $user)<tr><td><span class="admin-primary">{{ $user->name }}</span><span class="admin-secondary">{{ $user->email }}</span></td><td>{{ ucwords(str_replace('_', ' ', $user->role)) }}</td><td>{{ $user->created_at->diffForHumans() }}</td></tr>@endforeach
                </tbody></table></div>
            @else<div class="admin-empty">No users have registered yet.</div>@endif
        </div>
    </div>
</div>
@endsection