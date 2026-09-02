<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(['email' => $validated['email']]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'If that email belongs to an account, a password reset link has been sent.')
            : back()->withErrors(['email' => 'Unable to send a password reset link right now.']);
    }

    public function resetForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => request('email'), 'name' => request('name')]);
    }

    public function reset(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
        ]);

        $status = Password::reset($validated, function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successfully. You can now log in.')
            : back()->withErrors(['email' => 'This password reset link is invalid or expired.']);
    }
}