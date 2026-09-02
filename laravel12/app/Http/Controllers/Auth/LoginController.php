<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->dashboardRedirect(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();

            if ($user->status !== 'active') {
                return back()->withErrors(['email' => 'Your account is suspended.'])->withInput();
            }

            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return $this->dashboardRedirect($user->role);
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function dashboardRedirect(string $role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'moderator' => redirect()->route('moderator.dashboard'),
            'employer' => redirect()->route('employer.dashboard'),
            'job_seeker' => redirect()->route('job-seeker.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
