@extends('layouts.app')

@section('title', 'Job Portal - Find Your Dream Job')

@push('styles')
<style>
    .home-page { --home-ink: #18232d; --home-muted: #6d7a80; --home-line: #dce5e4; --home-teal: #087f67; --home-mint: #ccefe3; --home-coral: #f27864; color: var(--home-ink); }
    .home-page .hero-section { position: relative; overflow: hidden; margin: 0 0 30px; padding: clamp(55px, 8vw, 92px) 24px; border-radius: 0 0 24px 24px; color: #fff; background: #18232d; text-align: left; }
    .home-page .hero-section::after { position: absolute; right: -100px; bottom: -140px; width: 390px; height: 390px; border: 55px solid rgba(204,239,227,.14); border-radius: 50%; content: ''; }
    .home-page .hero-section .container { position: relative; z-index: 1; max-width: 980px; }
    .home-page .hero-section h1 { max-width: 700px; margin-bottom: 14px !important; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2.45rem, 6vw, 4.8rem); line-height: .98; letter-spacing: -.06em; }
    .home-page .hero-section p { max-width: 550px; color: #c4d2d2; font-size: 1.05rem; }
    .home-search { display: grid !important; grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr) 145px; gap: 10px !important; max-width: 850px; margin-top: 28px; }
    .home-search > [class*="col-"] { width: auto; flex: initial; max-width: none; padding-right: 0; padding-left: 0; }
    .home-search input { height: 54px; border: 0; border-radius: 9px !important; padding: 0 16px; box-shadow: none; }
    .home-search button { height: 54px; border: 0; border-radius: 9px !important; color: var(--home-ink); background: var(--home-mint); }
    .home-search button:hover { color: var(--home-ink); background: #fff; }
    .home-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin: 0 0 54px; }
    .home-stats > [class*="col-"] { width: auto; flex: initial; max-width: none; padding-right: 0; padding-left: 0; }
    .home-page .home-stats .stat-card { min-height: 112px; padding: 22px 14px; border: 1px solid var(--home-line); border-radius: 12px; box-shadow: none; }
    .home-page .home-stats .stat-card h3 { color: var(--home-teal); font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.8rem; }
    .home-page .home-stats .stat-card p { font-size: .8rem; }
    .home-section { margin-bottom: 58px; }
    .home-section-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
    .home-section-heading h2 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.7rem; letter-spacing: -.04em; }
    .home-section-heading p { margin: 5px 0 0; color: var(--home-muted); font-size: .86rem; }
    .home-category-card, .home-job-card { border: 1px solid var(--home-line) !important; border-radius: 12px !important; box-shadow: none !important; transition: transform .2s, border-color .2s, box-shadow .2s; }
    .home-category-card:hover { border-color: var(--home-teal) !important; box-shadow: 0 10px 25px rgba(24,35,45,.08) !important; transform: translateY(-3px); }
    .home-job-card { transition: none; }
    .home-job-card:hover { border-color: var(--home-line) !important; box-shadow: none !important; transform: none; }
    .home-category-icon { display: grid; place-items: center; width: 48px; height: 48px; margin: 0 auto 14px; border-radius: 12px; color: var(--home-teal); background: var(--home-mint); font-size: 1.35rem; }
    .home-category-card h5, .home-job-card h5 { font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .home-job-card .card-body { padding: 23px; }
    .home-job-card .badge { border-radius: 999px; padding: 7px 10px; color: var(--home-teal); background: var(--home-mint); }
    .home-page .btn-outline-primary { border-color: var(--home-teal); color: var(--home-teal); }
    .home-page .btn-outline-primary:hover { border-color: var(--home-teal); color: #fff; background: var(--home-teal); transform: none; }
    .home-page .btn-outline-primary i { display: inline-block; transition: transform .2s ease; }
    .home-page .btn-outline-primary:hover i { transform: translateX(4px); }
    .home-cta { border: 0 !important; border-radius: 14px !important; background: var(--home-teal) !important; }
    .home-cta .card-body { padding: clamp(32px, 6vw, 60px) 24px !important; }
    .home-cta h2 { font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    @media (max-width: 700px) { .home-search { grid-template-columns: 1fr; } .home-search input, .home-search button { width: 100%; } .home-stats { grid-template-columns: repeat(2, 1fr); } .home-section-heading { align-items: flex-start; flex-direction: column; } }
    @media (max-width: 420px) { .home-stats { gap: 8px; } .home-page .home-stats .stat-card { padding: 18px 8px; } }
</style>
@endpush

@section('content')
<div class="home-page">
<!-- Hero Section -->
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Find Your Dream Job Today</h1>
        <p class="lead mb-4">Connect with top employers and discover amazing opportunities</p>
        
        <!-- Search Bar -->
        <form action="{{ route('jobs.index') }}" method="GET" class="row g-3 justify-content-center home-search">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="Job title, keywords..." style="background: white;">
            </div>
            <div class="col-md-3">
                <input type="text" name="location" class="form-control form-control-lg" placeholder="Location" style="background: white;">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-light btn-lg w-100 fw-bold">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stats Section -->
<div class="home-stats">
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_jobs'] ?? '450+' }}</h3>
            <p>Live Jobs</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_companies'] ?? '180+' }}</h3>
            <p>Companies</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['total_candidates'] ?? '2000+' }}</h3>
            <p>Candidates</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <h3>{{ $stats['new_jobs'] ?? '20+' }}</h3>
            <p>New Jobs</p>
        </div>
    </div>
</div>

<!-- Popular Categories -->
<section class="home-section">
    <div class="home-section-heading"><div><h2>Popular job categories</h2><p>Explore opportunities by area of expertise.</p></div></div>
    <div class="row g-4">
        @if(isset($categories) && count($categories) > 0)
            @foreach($categories as $category)
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 text-center p-4 home-category-card">
                    <div class="home-category-icon">
                        <i class="bi bi-briefcase-fill" style="font-size: 2.5rem; color: var(--primary-purple);"></i>
                    </div>
                    <h5 class="fw-bold">{{ $category->name }}</h5>
                    <p class="text-muted mb-0">{{ $category->jobs_count ?? 0 }} jobs available</p>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 text-center p-4 home-category-card">
                    <div class="home-category-icon">
                        <i class="bi bi-laptop" style="font-size: 2.5rem; color: var(--primary-purple);"></i>
                    </div>
                    <h5 class="fw-bold">IT & Technology</h5>
                    <p class="text-muted mb-0">150+ jobs</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 text-center p-4 home-category-card">
                    <div class="home-category-icon">
                        <i class="bi bi-megaphone-fill" style="font-size: 2.5rem; color: var(--primary-purple);"></i>
                    </div>
                    <h5 class="fw-bold">Marketing</h5>
                    <p class="text-muted mb-0">80+ jobs</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 text-center p-4 home-category-card">
                    <div class="home-category-icon">
                        <i class="bi bi-cash-stack" style="font-size: 2.5rem; color: var(--primary-purple);"></i>
                    </div>
                    <h5 class="fw-bold">Finance</h5>
                    <p class="text-muted mb-0">60+ jobs</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-brush-fill" style="font-size: 2.5rem; color: var(--primary-purple);"></i>
                    </div>
                    <h5 class="fw-bold">Design</h5>
                    <p class="text-muted mb-0">45+ jobs</p>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Featured Jobs -->
<section class="home-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Featured jobs</h2>
        <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">View All <i class="bi bi-arrow-right"></i></a>
    </div>
    
    <div class="row g-4">
        @if(isset($featured_jobs) && count($featured_jobs) > 0)
            @foreach($featured_jobs as $job)
            <div class="col-md-6">
                <div class="card h-100 home-job-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-2">{{ $job->title }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-building"></i> {{ $job->employer->name ?? 'Company Name' }}
                                </p>
                            </div>
                            <span class="badge badge-purple">{{ $job->job_type }}</span>
                        </div>
                        <p class="text-muted mb-3">
                            <i class="bi bi-geo-alt-fill"></i> {{ $job->location }} &nbsp;
                            @if($job->salary_min && $job->salary_max)
                                <i class="bi bi-cash"></i> ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                            @endif
                        </p>
                        <p class="mb-3">{{ Str::limit($job->description, 100) }}</p>
                        <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-md-6">
                <div class="card h-100 home-job-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-2">Senior Laravel Developer</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-building"></i> Tech Solutions Inc.
                                </p>
                            </div>
                            <span class="badge badge-purple">Full-time</span>
                        </div>
                        <p class="text-muted mb-3">
                            <i class="bi bi-geo-alt-fill"></i> Remote &nbsp;
                            <i class="bi bi-cash"></i> $80,000 - $120,000
                        </p>
                        <p class="mb-3">We're looking for an experienced Laravel developer to join our growing team...</p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 home-job-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-2">Digital Marketing Manager</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-building"></i> Creative Agency
                                </p>
                            </div>
                            <span class="badge badge-purple">Full-time</span>
                        </div>
                        <p class="text-muted mb-3">
                            <i class="bi bi-geo-alt-fill"></i> New York, NY &nbsp;
                            <i class="bi bi-cash"></i> $60,000 - $90,000
                        </p>
                        <p class="mb-3">Join our creative team and lead innovative marketing campaigns...</p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="my-5">
    <div class="card home-cta" style="color: white;">
        <div class="card-body text-center p-5">
            <h2 class="fw-bold mb-3">Ready to Get Started?</h2>
            <p class="lead mb-4">Join thousands of job seekers and employers on our platform</p>
            <div>
                <a href="{{ route('register') }}?role=job_seeker" class="btn btn-light btn-lg me-2 fw-bold">
                    <i class="bi bi-person-plus"></i> Find Jobs
                </a>
                <a href="{{ route('register') }}?role=employer" class="btn btn-outline-light btn-lg fw-bold">
                    <i class="bi bi-briefcase"></i> Post Jobs
                </a>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
