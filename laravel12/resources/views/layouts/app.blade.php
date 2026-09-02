<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Job Portal - Find Your Dream Job')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Purple Theme Colors from your design */
            --primary-purple: #6C5CE7;
            --primary-purple-dark: #5B4ACF;
            --primary-purple-light: #A29BFE;
            --purple-soft: #F3F2FF;
            --purple-bg: #FAF9FF;
            
            /* Additional Colors */
            --dark-text: #2D3748;
            --gray-text: #718096;
            --light-bg: #F7FAFC;
            --white: #FFFFFF;
            --success: #48BB78;
            --warning: #ECC94B;
            --danger: #F56565;
            
            /* Shadows */
            --shadow-sm: 0 2px 4px rgba(108, 92, 231, 0.08);
            --shadow-md: 0 4px 12px rgba(108, 92, 231, 0.12);
            --shadow-lg: 0 8px 24px rgba(108, 92, 231, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DM Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f7f8f2;
            color: var(--dark-text);
            line-height: 1.6;
        }
        
        /* Navbar Styles */
        .navbar-custom {
            background: #18232d;
            box-shadow: 0 3px 14px rgba(24, 35, 45, .12);
            padding: .82rem 0;
        }

        .navbar-custom .container { gap: 24px; }
        
        .navbar-brand {
            font-family: 'Space Grotesk', 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-brand i {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            border-radius: 9px 9px 9px 3px;
            color: #18232d;
            background: #ccefe3;
            font-size: 1rem;
        }
        
        .nav-link {
            color: #cbd7d8 !important;
            font-weight: 500;
            padding: .48rem .78rem !important;
            transition: color .2s, background .2s;
            border-radius: 8px;
        }
        
        .nav-link:hover {
            color: #fff !important;
            background-color: rgba(204, 239, 227, .12);
        }
        
        .nav-link.active {
            color: #fff !important;
            background-color: rgba(204, 239, 227, .12);
        }

        .navbar-custom .dropdown-toggle { color: #fff !important; }
        .navbar-custom .dropdown-toggle::after { margin-left: 7px; color: #ccefe3; }
        .navbar-custom .dropdown-menu { margin-top: 12px; padding: 8px; border: 1px solid #dce5e4; border-radius: 10px; box-shadow: 0 12px 28px rgba(24, 35, 45, .14); }
        .navbar-custom .dropdown-item { border-radius: 7px; padding: 9px 11px; font-size: .88rem; }
        .navbar-custom .dropdown-item:hover { color: #087f67; background: #eaf8f2; }
        .navbar-custom .navbar-toggler { border-color: rgba(255,255,255,.3); }
        .navbar-custom .navbar-toggler-icon { filter: invert(1); }
        
        /* Button Styles */
        .btn-primary {
            background: #087f67;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            transition: background .2s;
            box-shadow: none;
        }
        
        .btn-primary:hover {
            background: #05634f;
            transform: none;
            box-shadow: none;
        }
        
        .btn-outline-primary {
            color: var(--primary-purple);
            border: 2px solid var(--primary-purple);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 12px;
            background: transparent;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-purple);
            color: var(--white);
            transform: translateY(-2px);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            background: var(--white);
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }
        
        .card-header {
            background: var(--purple-soft);
            border: none;
            border-radius: 16px 16px 0 0 !important;
            padding: 1.25rem;
            font-weight: 600;
            color: var(--primary-purple);
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        .badge-purple {
            background: var(--purple-soft);
            color: var(--primary-purple);
        }
        
        .badge-success {
            background: #E6FFFA;
            color: var(--success);
        }
        
        .badge-warning {
            background: #FFFBEB;
            color: var(--warning);
        }
        
        .badge-danger {
            background: #FFF5F5;
            color: var(--danger);
        }
        
        /* Form Styles */
        .form-control, .form-select {
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px var(--purple-soft);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }
        
        /* Container */
        .content-wrapper {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--primary-purple-light) 100%);
            color: var(--white);
            padding: 4rem 0;
            border-radius: 24px;
            margin: 2rem 0;
        }
        
        /* Stats Card */
        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-purple);
            margin: 0;
        }
        
        .stat-card p {
            color: var(--gray-text);
            margin: 0.5rem 0 0 0;
            font-weight: 500;
        }
        
        /* Footer */
        .footer {
            background: #18232d;
            padding: 28px 0;
            margin-top: 3rem;
            border-top: 0;
            color: #b7c6c8;
        }

        .footer h5 { font-family: 'Space Grotesk', 'DM Sans', sans-serif; color: #fff !important; }
        .footer p { margin-bottom: 0; color: #9eb0b2 !important; font-size: .82rem; }
        .footer .text-danger { color: #f27864 !important; }
        
        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }
        
        .alert-success {
            background: #E6FFFA;
            color: var(--success);
        }
        
        .alert-danger {
            background: #FFF5F5;
            color: var(--danger);
        }
        
        /* Table Styles */
        .table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }
        
        .table thead th {
            background: var(--purple-soft);
            color: var(--primary-purple);
            font-weight: 600;
            border: none;
            padding: 1rem;
        }
        
        .table tbody tr {
            background: var(--white);
            box-shadow: var(--shadow-sm);
        }
        
        .table tbody td {
            padding: 1rem;
            border: none;
            vertical-align: middle;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .hero-section {
                padding: 2rem 0;
            }
            
            .stat-card h3 {
                font-size: 1.5rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-briefcase-fill"></i>
                <span>JobPortal</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('jobs.index') }}">Browse Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">Sign Up</a>
                        </li>
                    @else
                        @if(auth()->user()->isJobSeeker())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('job-seeker.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('jobs.index') }}">Browse Jobs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('job-seeker.applications') }}">My Applications</a>
                            </li>
                        @elseif(auth()->user()->isEmployer())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employer.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employer.jobs') }}">My Jobs</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary btn-sm ms-2" href="{{ route('employer.jobs.create') }}">Post Job</a>
                            </li>
                        @elseif(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users') }}">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.jobs') }}">Jobs</a>
                            </li>
                        @elseif(auth()->user()->isModerator())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('moderator.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('moderator.jobs') }}">Review Jobs</a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(auth()->user()->isJobSeeker())
                                    <li><a class="dropdown-item" href="{{ route('job-seeker.profile') }}">My Profile</a></li>
                                @elseif(auth()->user()->isEmployer())
                                    <li><a class="dropdown-item" href="{{ route('employer.profile') }}">Company Profile</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3" style="color: var(--primary-purple);">
                        <i class="bi bi-briefcase-fill"></i> JobPortal
                    </h5>
                    <p class="text-muted">Find your dream job or hire the best talent.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} JobPortal. All rights reserved.</p>
                    <p class="text-muted">Built with <i class="bi bi-heart-fill text-danger"></i> by BrainBrick</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
