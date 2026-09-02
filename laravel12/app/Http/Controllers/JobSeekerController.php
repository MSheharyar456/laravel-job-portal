<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobSeekerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $profile = $user->jobSeekerProfile;
        
        $recentApplications = \App\Models\JobApplication::with('job.employer.user')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
        
        $stats = [
            'total_applications' => \App\Models\JobApplication::where('user_id', $user->id)->count(),
            'pending' => \App\Models\JobApplication::where('user_id', $user->id)->where('status', 'pending')->count(),
            'accepted' => \App\Models\JobApplication::where('user_id', $user->id)->where('status', 'accepted')->count(),
            'rejected' => \App\Models\JobApplication::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('job-seeker.dashboard', compact('profile', 'recentApplications', 'stats'));
    }

    public function profile()
    {
        $profile = auth()->user()->jobSeekerProfile;
        return view('job-seeker.profile', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'education' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $user = auth()->user();
        $profile = $user->jobSeekerProfile ?? $user->jobSeekerProfile()->create();

        if ($request->hasFile('resume')) {
            // Delete old resume if exists
            if ($profile->resume_path) {
                \Illuminate\Support\Facades\Storage::delete($profile->resume_path);
            }
            $validated['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        $profile->update($validated);

        $user->update($request->only(['name', 'phone', 'location']));

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Profile updated successfully!']);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function applications(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $perPage = in_array((int) $request->input('per_page', 10), [5, 10, 20, 50], true)
            ? (int) $request->input('per_page', 10)
            : 10;
        $applications = \App\Models\JobApplication::with('job.employer.user')
            ->where('user_id', auth()->id())
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('job', function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhereHas('employer.user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('job-seeker._applications_results', compact('applications', 'search', 'perPage'));
        }

        return view('job-seeker.applications', compact('applications', 'search', 'perPage'));
    }

    public function showApplyForm($jobId)
    {
        $job = \App\Models\Job::with('employer.user')
            ->where('status', 'approved')
            ->findOrFail($jobId);
        
        // Check if already applied
        $hasApplied = \App\Models\JobApplication::where('job_id', $jobId)
            ->where('user_id', auth()->id())
            ->exists();

        if ($hasApplied) {
            return redirect()->route('jobs.show', $jobId)
                ->with('error', 'You have already applied for this job.');
        }

        $profile = auth()->user()->jobSeekerProfile;

        return view('job-seeker.apply', compact('job', 'profile'));
    }

    public function apply(Request $request, $jobId)
    {
        $job = \App\Models\Job::findOrFail($jobId);
        
        // Check if already applied
        $exists = \App\Models\JobApplication::where('job_id', $jobId)
            ->where('user_id', auth()->id())
            ->exists();

        if ($exists) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You have already applied for this job.'], 422);
            }

            return back()->with('error', 'You have already applied for this job.');
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('applications', 'public');
        } else {
            // Use profile resume if no new resume uploaded
            $resumePath = optional(auth()->user()->jobSeekerProfile)->resume_path;
        }

        if (! $resumePath) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Please upload a CV before submitting your application.'], 422);
            }

            return back()->withErrors(['resume' => 'Please upload a CV before submitting your application.'])->withInput();
        }

        try {
            \App\Models\JobApplication::create([
                'job_id' => $jobId,
                'user_id' => auth()->id(),
                'cover_letter' => $validated['cover_letter'] ?? null,
                'resume_path' => $resumePath,
                'status' => 'pending',
            ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'You have already applied for this job.'], 422);
            }

            return back()->with('error', 'You have already applied for this job.');
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Application submitted successfully!']);
        }

        return redirect()->route('job-seeker.applications')
            ->with('success', 'Application submitted successfully!');
    }
}
