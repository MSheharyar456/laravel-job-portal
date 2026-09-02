<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'job_seekers' => \App\Models\User::where('role', 'job_seeker')->count(),
            'employers' => \App\Models\User::where('role', 'employer')->count(),
            'total_jobs' => \App\Models\Job::count(),
            'active_jobs' => \App\Models\Job::where('status', 'approved')->count(),
            'pending_jobs' => \App\Models\Job::where('status', 'pending')->count(),
            'total_applications' => \App\Models\JobApplication::count(),
            'total_categories' => \App\Models\Category::count(),
        ];

        $recentUsers = \App\Models\User::latest()->limit(5)->get();
        $recentJobs = \App\Models\Job::with('employer.user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentJobs'));
    }

    public function users(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $perPage = in_array((int) $request->input('per_page', 20), [5, 10, 20, 50], true)
            ? (int) $request->input('per_page', 20)
            : 20;
        $users = \App\Models\User::when($search !== '', function ($query) use ($search) {
                $query->where(function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin._users_results', compact('users', 'search', 'perPage'));
        }

        return view('admin.users', compact('users', 'search', 'perPage'));
    }

    public function deleteUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $validated = $request->validate([
            'role' => 'required|in:job_seeker,employer,moderator,admin',
        ]);

        $user->update($validated);
        return back()->with('success', 'User role updated successfully!');
    }

    public function updateUserStatus($id)
    {
        $user = \App\Models\User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own account!');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'suspended' : 'active',
        ]);

        return back()->with('success', 'User status updated successfully!');
    }

    public function jobs(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', '');
        $status = in_array($status, ['pending', 'approved', 'rejected'], true) ? $status : '';
        $perPage = in_array((int) $request->input('per_page', 20), [5, 10, 20, 50], true)
            ? (int) $request->input('per_page', 20)
            : 20;
        $jobs = \App\Models\Job::with(['employer.user', 'category'])
            ->withCount('applications')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('employer.user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin._jobs_results', compact('jobs', 'search', 'status', 'perPage'));
        }

        return view('admin.jobs', compact('jobs', 'search', 'status', 'perPage'));
    }

    public function deleteJob($id)
    {
        $job = \App\Models\Job::findOrFail($id);
        $job->delete();
        return back()->with('success', 'Job deleted successfully!');
    }

    public function updateJobStatus(Request $request, $id)
    {
        $job = \App\Models\Job::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $job->update($validated);
        return back()->with('success', 'Job status updated successfully!');
    }

    public function categories()
    {
        $categories = \App\Models\Category::withCount('jobs')->paginate(20);
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        \App\Models\Category::create($validated);
        return back()->with('success', 'Category created successfully!');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);
        return back()->with('success', 'Category updated successfully!');
    }

    public function deleteCategory($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        
        // Check if category has jobs
        if ($category->jobs()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing jobs!');
        }

        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}
