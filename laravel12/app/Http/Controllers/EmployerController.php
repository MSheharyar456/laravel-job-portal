<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function dashboard(Request $request)
    {
        $employer = auth()->user()->employer;
        $search = trim((string) $request->input('search', ''));
        $perPage = in_array((int) $request->input('per_page', 6), [5, 6, 10, 20, 50], true)
            ? (int) $request->input('per_page', 6)
            : 6;

        $stats = [
            'total_jobs' => \App\Models\Job::where('employer_id', $employer->id)->count(),
            'active_jobs' => \App\Models\Job::where('employer_id', $employer->id)->where('status', 'approved')->count(),
            'pending_jobs' => \App\Models\Job::where('employer_id', $employer->id)->where('status', 'pending')->count(),
            'total_applications' => \App\Models\JobApplication::whereHas('job', function($q) use ($employer) {
                $q->where('employer_id', $employer->id);
            })->count(),
        ];

        $jobs = \App\Models\Job::where('employer_id', $employer->id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('job_type', 'like', "%{$search}%");
                });
            })
            ->with('category')
            ->withCount('applications')
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        $recentApplications = \App\Models\JobApplication::with(['job', 'user'])
            ->whereHas('job', function($q) use ($employer) {
                $q->where('employer_id', $employer->id);
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('employer.dashboard', compact('stats', 'jobs', 'recentApplications', 'perPage', 'search'));
    }

    public function profile()
    {
        $employer = auth()->user()->employer;
        return view('employer.profile', compact('employer'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $employer = auth()->user()->employer;

        if ($request->hasFile('company_logo')) {
            if ($employer->company_logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($employer->company_logo);
            }
            $validated['company_logo'] = $request->file('company_logo')->store('company-logos', 'public');
        }

        $employer->update($validated);

        if ($request->filled('phone')) {
            auth()->user()->update(['phone' => $request->phone]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function jobs(Request $request)
    {
        $search = trim((string) request('search'));
        $perPage = in_array((int) request('per_page', 10), [5, 10, 20, 50], true)
            ? (int) request('per_page', 10)
            : 10;

        $jobs = \App\Models\Job::where('employer_id', auth()->user()->employer->id)
            ->with('category')
            ->withCount('applications')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('employer.jobs._results', compact('jobs', 'search', 'perPage'));
        }

        return view('employer.jobs.index', compact('jobs', 'search', 'perPage'));
    }

    public function createJob()
    {
        $categories = \App\Models\Category::all();
        return view('employer.jobs.create', compact('categories'));
    }

    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'job_type' => 'required|in:full-time,part-time,contract,remote',
            'deadline' => 'nullable|date|after_or_equal:today',
            'category_id' => 'required|exists:categories,id',
        ]);

        $employerId = auth()->user()->employer->id;
        $duplicate = \App\Models\Job::where('employer_id', $employerId)
            ->where('title', $validated['title'])
            ->where('location', $validated['location'])
            ->where('category_id', $validated['category_id'])
            ->where('job_type', $validated['job_type'])
            ->where('description', $validated['description'])
            ->exists();

        if ($duplicate) {
            return back()
                ->withErrors(['title' => 'You have already posted this same job.'])
                ->withInput();
        }

        $validated['employer_id'] = $employerId;
        $validated['status'] = 'pending'; // Requires moderation

        \App\Models\Job::create($validated);

        return redirect()->route('employer.jobs')
            ->with('success', 'Job posted successfully! Waiting for moderation approval.');
    }

    public function editJob($id)
    {
        $job = \App\Models\Job::where('employer_id', auth()->user()->employer->id)
            ->findOrFail($id);
        $categories = \App\Models\Category::all();

        return view('employer.jobs.edit', compact('job', 'categories'));
    }

    public function updateJob(Request $request, $id)
    {
        $job = \App\Models\Job::where('employer_id', auth()->user()->employer->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'job_type' => 'required|in:full-time,part-time,contract,remote',
            'deadline' => 'nullable|date|after_or_equal:today',
            'category_id' => 'required|exists:categories,id',
        ]);

        $duplicate = \App\Models\Job::where('employer_id', auth()->user()->employer->id)
            ->where('id', '!=', $job->id)
            ->where('title', $validated['title'])
            ->where('location', $validated['location'])
            ->where('category_id', $validated['category_id'])
            ->where('job_type', $validated['job_type'])
            ->where('description', $validated['description'])
            ->exists();

        if ($duplicate) {
            return back()
                ->withErrors(['title' => 'Another job with the same details already exists.'])
                ->withInput();
        }

        $job->update($validated);

        return redirect()->route('employer.jobs')
            ->with('success', 'Job updated successfully!');
    }

    public function deleteJob(Request $request, $id)
    {
        $job = \App\Models\Job::where('employer_id', auth()->user()->employer->id)
            ->findOrFail($id);

        abort_unless(in_array($job->status, ['pending', 'rejected'], true), 403);
        $job->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Job deleted successfully!');
    }

    public function jobApplications(Request $request, $jobId)
    {
        $job = \App\Models\Job::where('employer_id', auth()->user()->employer->id)
            ->findOrFail($jobId);

        $search = trim((string) $request->input('search', ''));
        $perPage = in_array((int) $request->input('per_page', 10), [5, 10, 20, 50], true)
            ? (int) $request->input('per_page', 10)
            : 10;

        $applications = \App\Models\JobApplication::with(['user.jobSeekerProfile'])
            ->where('job_id', $jobId)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($applicationQuery) use ($search) {
                    $applicationQuery->where('status', 'like', "%{$search}%")
                        ->orWhere('cover_letter', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('employer.jobs._applications_results', compact('applications', 'search', 'perPage'));
        }

        return view('employer.jobs.applications', compact('job', 'applications', 'search', 'perPage'));
    }

    public function updateApplicationStatus(Request $request, $applicationId)
    {
        $application = \App\Models\JobApplication::with('job')
            ->findOrFail($applicationId);

        // Verify employer owns this job
        if ($application->job->employer_id !== auth()->user()->employer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,accepted,rejected',
        ]);

        $application->update($validated);

        return back()->with('success', 'Application status updated!');
    }
}
