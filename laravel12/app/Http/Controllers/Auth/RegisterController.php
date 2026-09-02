<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role' => 'required|in:job_seeker,employer',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        // Create related profile based on role
        if ($user->role === 'job_seeker') {
            \App\Models\JobSeekerProfile::create([
                'user_id' => $user->id,
            ]);
        } elseif ($user->role === 'employer') {
            \App\Models\Employer::create([
                'user_id' => $user->id,
                'company_name' => $validated['name'],
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Registration successful. Please log in to continue.');
    }
}
