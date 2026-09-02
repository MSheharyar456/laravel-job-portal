<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Job::with(['employer.user', 'category'])
            ->where('status', 'approved');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('job_type', $request->type);
        }

        $jobs = $query->latest()->paginate(12)->withQueryString();
        $categories = \App\Models\Category::all();

        if ($request->ajax()) {
            return view('jobs._results', compact('jobs'));
        }

        return view('jobs.index', compact('jobs', 'categories'));
    }

    public function show($id)
    {
        $job = \App\Models\Job::with(['employer.user', 'category'])
            ->withCount('applications')
            ->where('status', 'approved')
            ->findOrFail($id);
        
        // Check if user already applied
        $hasApplied = false;
        if (auth()->check() && auth()->user()->role === 'job_seeker') {
            $hasApplied = \App\Models\JobApplication::where('job_id', $job->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('jobs.show', compact('job', 'hasApplied'));
    }
}
