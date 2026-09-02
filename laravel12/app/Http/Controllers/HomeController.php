<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::withCount('jobs')->take(8)->get();
        $featured_jobs = \App\Models\Job::where('status', 'approved')
            ->with(['employer', 'category'])
            ->latest()
            ->take(6)
            ->get();
        
        $stats = [
            'total_jobs' => \App\Models\Job::where('status', 'approved')->count(),
            'total_companies' => \App\Models\Employer::count(),
            'total_candidates' => \App\Models\User::where('role', 'job_seeker')->count(),
            'new_jobs' => \App\Models\Job::where('status', 'approved')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];
        
        return view('home.index', compact('categories', 'featured_jobs', 'stats'));
    }
}
