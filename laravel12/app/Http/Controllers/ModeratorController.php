<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'pending_jobs' => \App\Models\Job::where('status', 'pending')->count(),
            'approved_today' => \App\Models\Job::where('status', 'approved')
                ->whereDate('updated_at', today())->count(),
            'rejected_today' => \App\Models\Job::where('status', 'rejected')
                ->whereDate('updated_at', today())->count(),
            'total_jobs' => \App\Models\Job::count(),
        ];

        $search = trim((string) $request->input('search', ''));
        $perPage = in_array((int) $request->input('per_page', 10), [5, 10, 20, 50], true)
            ? (int) $request->input('per_page', 10)
            : 10;

        $pendingJobs = \App\Models\Job::with(['employer.user', 'category'])
            ->withCount('applications')
            ->where('status', 'pending')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('moderator._dashboard_results', compact('pendingJobs', 'search', 'perPage'));
        }

        return view('moderator.dashboard', compact('stats', 'pendingJobs', 'search', 'perPage'));
    }

    public function jobs(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $perPage = in_array((int) $request->input('per_page', 20), [5, 10, 20, 50], true)
            ? (int) $request->input('per_page', 20)
            : 20;
        $query = \App\Models\Job::with(['employer.user', 'category'])
            ->withCount('applications')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });

        // Filter by status
        $status = $request->input('status', 'pending');
        $status = $status === 'all' ? '' : (in_array($status, ['pending', 'approved', 'rejected'], true) ? $status : 'pending');

        if ($status !== '') {
            $query->where('status', $status);
        }

        $jobs = $query->latest()->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('moderator._jobs_results', compact('jobs', 'search', 'perPage', 'status'));
        }

        return view('moderator.jobs', compact('jobs', 'search', 'perPage', 'status'));
    }

    public function approveJob($id)
    {
        $job = \App\Models\Job::findOrFail($id);
        $job->update(['status' => 'approved']);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Job approved successfully.']);
        }

        return back()->with('success', 'Job approved successfully!');
    }

    public function rejectJob($id)
    {
        $job = \App\Models\Job::findOrFail($id);
        $job->update(['status' => 'rejected']);

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Job rejected successfully.']);
        }

        return back()->with('success', 'Job rejected successfully!');
    }

    public function updateJobStatus(Request $request, $id)
    {
        $job = \App\Models\Job::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $job->update($validated);

        return back()->with('success', 'Job status updated to ' . $validated['status'] . '!');
    }
}
