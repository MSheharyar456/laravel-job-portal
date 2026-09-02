<?php
/**
 * Run this file once to create all remaining views
 * Usage: php CREATE_REMAINING_VIEWS.php
 */

$views = [
    // Job Seeker Views
    'resources/views/job-seeker/profile.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'My Profile - Job Seeker')
@section('content')
<div class="my-4">
    <h1 class="fw-bold mb-4">My Profile</h1>
    <form method="POST" action="{{ route('job-seeker.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">Personal Information</h5></div>
                    <div class="card-body">
                        <div class="mb-3"><label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required></div>
                        <div class="mb-3"><label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled></div>
                        <div class="mb-3"><label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}"></div>
                        <div class="mb-3"><label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" value="{{ auth()->user()->location }}"></div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">Professional Details</h5></div>
                    <div class="card-body">
                        <div class="mb-3"><label class="form-label">Skills</label>
                            <textarea name="skills" class="form-control" rows="3" placeholder="Laravel, PHP, JavaScript...">{{ $profile->skills ?? '' }}</textarea></div>
                        <div class="mb-3"><label class="form-label">Experience</label>
                            <textarea name="experience" class="form-control" rows="4">{{ $profile->experience ?? '' }}</textarea></div>
                        <div class="mb-3"><label class="form-label">Education</label>
                            <textarea name="education" class="form-control" rows="4">{{ $profile->education ?? '' }}</textarea></div>
                        <div class="mb-3"><label class="form-label">Resume/CV</label>
                            <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                            @if($profile && $profile->resume_path)<p class="mt-2"><a href="{{ Storage::url($profile->resume_path) }}" target="_blank">View Current Resume</a></p>@endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card"><div class="card-body text-center">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Profile</button>
                    </div></div>
            </div>
        </div>
    </form>
</div>
@endsection
BLADE,

    'resources/views/job-seeker/applications.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'My Applications')
@section('content')
<div class="my-4">
    <h1 class="fw-bold mb-4">My Applications</h1>
    @if(isset($applications) && count($applications) > 0)
        <div class="card"><div class="card-body"><table class="table">
                    <thead><tr><th>Job Title</th><th>Company</th><th>Applied Date</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr><td><a href="{{ route('jobs.show', $app->job_id) }}">{{ $app->job->title }}</a></td>
                            <td>{{ $app->job->employer->name ?? 'N/A' }}</td>
                            <td>{{ $app->created_at->format('M d, Y') }}</td>
                            <td>@if($app->status == 'pending')<span class="badge badge-warning">Pending</span>
                                @elseif($app->status == 'accepted')<span class="badge badge-success">Accepted</span>
                                @else<span class="badge badge-danger">Rejected</span>@endif</td>
                            <td><a href="{{ route('jobs.show', $app->job_id) }}" class="btn btn-sm btn-outline-primary">View Job</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div></div>
    @else
        <div class="card text-center p-5"><p>No applications yet</p><a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a></div>
    @endif
</div>
@endsection
BLADE,

    'resources/views/job-seeker/apply.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Apply for Job')
@section('content')
<div class="my-4"><div class="row justify-content-center"><div class="col-md-8">
                <div class="card"><div class="card-header"><h4 class="mb-0">Apply for: {{ $job->title }}</h4></div>
                    <div class="card-body"><form method="POST" action="{{ route('job-seeker.jobs.apply.submit', $job->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3"><label class="form-label">Cover Letter</label>
                                <textarea name="cover_letter" class="form-control" rows="6" required placeholder="Tell the employer why you're a great fit..."></textarea></div>
                            <div class="mb-3"><label class="form-label">Resume</label>
                                <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Or we'll use your profile resume</small></div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Submit Application</button>
                            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-secondary">Cancel</a>
                        </form></div>
                </div>
            </div></div></div>
@endsection
BLADE,

    // Employer Views
    'resources/views/employer/dashboard.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Employer Dashboard')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Employer Dashboard</h1>
    <div class="row g-4 mb-4">
        <div class="col-md-3"><div class="stat-card"><h3>{{ $stats['total_jobs'] ?? 0 }}</h3><p>Posted Jobs</p></div></div>
        <div class="col-md-3"><div class="stat-card"><h3>{{ $stats['active_jobs'] ?? 0 }}</h3><p>Active Jobs</p></div></div>
        <div class="col-md-3"><div class="stat-card"><h3>{{ $stats['pending_jobs'] ?? 0 }}</h3><p>Pending Approval</p></div></div>
        <div class="col-md-3"><div class="stat-card"><h3>{{ $stats['total_applications'] ?? 0 }}</h3><p>Applications</p></div></div>
    </div>
    <div class="d-flex justify-content-between mb-3">
        <h3>Recent Jobs</h3>
        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Post New Job</a>
    </div>
    <div class="card"><div class="card-body">
            @if(isset($recent_jobs) && count($recent_jobs) > 0)
                <table class="table"><thead><tr><th>Title</th><th>Status</th><th>Applications</th><th>Posted</th><th>Actions</th></tr></thead>
                    <tbody>@foreach($recent_jobs as $job)
                        <tr><td>{{ $job->title }}</td>
                            <td><span class="badge badge-{{ $job->status == 'approved' ? 'success' : ($job->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($job->status) }}</span></td>
                            <td>{{ $job->applications->count() }}</td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                            <td><a href="{{ route('employer.jobs.applications', $job->id) }}" class="btn btn-sm btn-outline-primary">View Applications</a></td>
                        </tr>@endforeach
                    </tbody>
                </table>
            @else<p class="text-center text-muted">No jobs posted yet</p>@endif
        </div></div>
</div>
@endsection
BLADE,

    'resources/views/employer/profile.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Company Profile')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Company Profile</h1>
    <form method="POST" action="{{ route('employer.profile.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
        <div class="card"><div class="card-body">
                <div class="mb-3"><label class="form-label">Company Name</label><input type="text" name="company_name" class="form-control" value="{{ $employer->company_name ?? '' }}" required></div>
                <div class="mb-3"><label class="form-label">Company Description</label><textarea name="company_description" class="form-control" rows="4">{{ $employer->company_description ?? '' }}</textarea></div>
                <div class="mb-3"><label class="form-label">Industry</label><input type="text" name="industry" class="form-control" value="{{ $employer->industry ?? '' }}"></div>
                <div class="mb-3"><label class="form-label">Website</label><input type="url" name="website" class="form-control" value="{{ $employer->website ?? '' }}"></div>
                <div class="mb-3"><label class="form-label">Company Logo</label><input type="file" name="company_logo" class="form-control" accept="image/*"></div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Profile</button>
            </div></div>
    </form>
</div>
@endsection
BLADE,

    'resources/views/employer/jobs/index.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'My Jobs')
@section('content')
<div class="my-4"><div class="d-flex justify-content-between mb-4">
        <h1 class="fw-bold">My Posted Jobs</h1>
        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Post New Job</a>
    </div>
    @if(isset($jobs) && count($jobs) > 0)
        <div class="card"><div class="card-body"><table class="table">
                    <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Applications</th><th>Actions</th></tr></thead>
                    <tbody>@foreach($jobs as $job)
                        <tr><td>{{ $job->title }}</td><td>{{ $job->category->name ?? 'N/A' }}</td>
                            <td><span class="badge badge-{{ $job->status == 'approved' ? 'success' : ($job->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($job->status) }}</span></td>
                            <td>{{ $job->applications->count() }}</td>
                            <td><a href="{{ route('employer.jobs.edit', $job->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="{{ route('employer.jobs.applications', $job->id) }}" class="btn btn-sm btn-primary">Applications</a></td>
                        </tr>@endforeach
                    </tbody>
                </table></div></div>
    @else<div class="card text-center p-5"><p>No jobs posted yet</p></div>@endif
</div>
@endsection
BLADE,

    'resources/views/employer/jobs/create.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Post New Job')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Post New Job</h1>
    <form method="POST" action="{{ route('employer.jobs.store') }}">@csrf
        <div class="card"><div class="card-body">
                <div class="mb-3"><label class="form-label">Job Title</label><input type="text" name="title" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Category</label><select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories ?? [] as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                    </select></div>
                <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="6" required></textarea></div>
                <div class="mb-3"><label class="form-label">Requirements</label><textarea name="requirements" class="form-control" rows="4"></textarea></div>
                <div class="row"><div class="col-md-6 mb-3"><label class="form-label">Salary Min</label><input type="number" name="salary_min" class="form-control"></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Salary Max</label><input type="number" name="salary_max" class="form-control"></div></div>
                <div class="mb-3"><label class="form-label">Location</label><input type="text" name="location" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Job Type</label><select name="job_type" class="form-select" required>
                        <option value="full-time">Full-time</option><option value="part-time">Part-time</option>
                        <option value="contract">Contract</option><option value="remote">Remote</option></select></div>
                <div class="mb-3"><label class="form-label">Application Deadline</label><input type="date" name="deadline" class="form-control"></div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Submit for Approval</button>
            </div></div>
    </form>
</div>
@endsection
BLADE,

    'resources/views/employer/jobs/applications.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Job Applications')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Applications for: {{ $job->title }}</h1>
    @if(count($applications) > 0)
        @foreach($applications as $app)
            <div class="card mb-3"><div class="card-body">
                    <div class="d-flex justify-content-between"><div><h5>{{ $app->user->name }}</h5>
                            <p class="text-muted mb-2"><i class="bi bi-envelope"></i> {{ $app->user->email }}</p>
                            <p><strong>Cover Letter:</strong><br>{{ $app->cover_letter }}</p></div>
                        <div><span class="badge badge-{{ $app->status == 'pending' ? 'warning' : ($app->status == 'accepted' ? 'success' : 'danger') }}">{{ ucfirst($app->status) }}</span></div></div>
                    @if($app->status == 'pending')
                        <form method="POST" action="{{ route('employer.applications.accept', $app->id) }}" class="d-inline">@csrf<button class="btn btn-success btn-sm">Accept</button></form>
                        <form method="POST" action="{{ route('employer.applications.reject', $app->id) }}" class="d-inline">@csrf<button class="btn btn-danger btn-sm">Reject</button></form>
                    @endif
                </div></div>
        @endforeach
    @else<div class="card text-center p-5"><p>No applications yet</p></div>@endif
</div>
@endsection
BLADE,

    // Admin Views  
    'resources/views/admin/dashboard.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Admin Dashboard</h1>
    <div class="row g-4">
        <div class="col-md-3"><div class="stat-card"><h3>{{ $stats['total_users'] ?? 0 }}</h3><p>Total Users</p></div></div>
        <div class="col-md-3"><div class="stat-card"><h3>{{ $stats['total_jobs'] ?? 0 }}</h3><p>Total Jobs</p></div></div>
        <div class="col-md-3"><div class="stat-card"><h3>{{ $stats['pending_jobs'] ?? 0 }}</h3><p>Pending Approval</p></div></div>
        <div class="col-md-3"><div class="stat-card"><h3>{{ $stats['total_applications'] ?? 0 }}</h3><p>Applications</p></div></div>
    </div>
</div>
@endsection
BLADE,

    'resources/views/admin/users.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Manage Users</h1>
    <div class="card"><div class="card-body"><table class="table">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>@foreach($users ?? [] as $user)
                    <tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td>
                        <td><span class="badge badge-purple">{{ ucfirst($user->role) }}</span></td>
                        <td><span class="badge badge-{{ $user->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($user->status) }}</span></td>
                        <td><form method="POST" action="{{ route('admin.users.status', $user->id) }}" class="d-inline">@csrf @method('PUT')
                                <button class="btn btn-sm btn-outline-primary">Toggle Status</button></form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete user?')">Delete</button></form></td>
                    </tr>@endforeach
                </tbody>
            </table></div></div>
</div>
@endsection
BLADE,

    'resources/views/admin/jobs.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Manage Jobs')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Manage Jobs</h1>
    <div class="card"><div class="card-body"><table class="table">
                <thead><tr><th>Title</th><th>Company</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>@foreach($jobs ?? [] as $job)
                    <tr><td>{{ $job->title }}</td><td>{{ $job->employer->name ?? 'N/A' }}</td>
                        <td><span class="badge badge-{{ $job->status == 'approved' ? 'success' : ($job->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($job->status) }}</span></td>
                        <td>@if($job->status == 'pending')
                                <form method="POST" action="{{ route('admin.jobs.approve', $job->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                                <form method="POST" action="{{ route('admin.jobs.reject', $job->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-danger">Reject</button></form>
                            @endif
                            <form method="POST" action="{{ route('admin.jobs.destroy', $job->id) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
                    </tr>@endforeach
                </tbody>
            </table></div></div>
</div>
@endsection
BLADE,

    'resources/views/admin/categories.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Manage Categories')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Manage Categories</h1>
    <div class="row"><div class="col-md-4"><div class="card"><div class="card-header">Add Category</div>
                    <div class="card-body"><form method="POST" action="{{ route('admin.categories.store') }}">@csrf
                            <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Category Name" required></div>
                            <button type="submit" class="btn btn-primary w-100">Add Category</button></form></div></div></div>
        <div class="col-md-8"><div class="card"><div class="card-body"><table class="table">
                        <thead><tr><th>Name</th><th>Jobs Count</th><th>Actions</th></tr></thead>
                        <tbody>@foreach($categories ?? [] as $cat)
                            <tr><td>{{ $cat->name }}</td><td>{{ $cat->jobs_count ?? 0 }}</td>
                                <td><form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" class="d-inline">@csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach
                        </tbody></table></div></div></div></div>
</div>
@endsection
BLADE,

    // Moderator Views
    'resources/views/moderator/dashboard.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Moderator Dashboard')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Moderator Dashboard</h1>
    <div class="row g-4 mb-4">
        <div class="col-md-4"><div class="stat-card"><h3>{{ $stats['pending_jobs'] ?? 0 }}</h3><p>Pending Approval</p></div></div>
        <div class="col-md-4"><div class="stat-card"><h3>{{ $stats['approved_today'] ?? 0 }}</h3><p>Approved Today</p></div></div>
        <div class="col-md-4"><div class="stat-card"><h3>{{ $stats['rejected_today'] ?? 0 }}</h3><p>Rejected Today</p></div></div>
    </div>
    <a href="{{ route('moderator.jobs') }}" class="btn btn-primary"><i class="bi bi-list-check"></i> Review Pending Jobs</a>
</div>
@endsection
BLADE,

    'resources/views/moderator/jobs.blade.php' => <<<'BLADE'
@extends('layouts.app')
@section('title', 'Review Jobs')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Review Jobs</h1>
    @if(count($jobs ?? []) > 0)
        @foreach($jobs as $job)
            <div class="card mb-3"><div class="card-body">
                    <div class="d-flex justify-content-between"><div><h5>{{ $job->title }}</h5>
                            <p class="text-muted">{{ $job->employer->name ?? 'N/A' }} | {{ $job->location }}</p>
                            <p>{{ Str::limit($job->description, 200) }}</p></div>
                        <div><span class="badge badge-warning">Pending</span></div></div>
                    <form method="POST" action="{{ route('moderator.jobs.approve', $job->id) }}" class="d-inline">@csrf<button class="btn btn-success">Approve</button></form>
                    <form method="POST" action="{{ route('moderator.jobs.reject', $job->id) }}" class="d-inline">@csrf<button class="btn btn-danger">Reject</button></form>
                </div></div>
        @endforeach
    @else<div class="card text-center p-5"><p>No pending jobs to review</p></div>@endif
</div>
@endsection
BLADE,
];

foreach ($views as $path => $content) {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($path, $content);
    echo "Created: $path\n";
}

echo "\n✅ All views created successfully!\n";
