<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password | Job Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --ink: #18232d; --muted: #6d7a80; --line: #dce5e4; --mint: #ccefe3; --green: #087f67; --cream: #f7f8f2; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; color: var(--ink); background: var(--cream); font-family: 'DM Sans', sans-serif; }
        .auth-card { width: min(100%, 460px); padding: 38px; border: 1px solid var(--line); border-radius: 14px; background: #fff; }
        .brand { display: inline-flex; align-items: center; gap: 9px; margin-bottom: 34px; color: var(--ink); font: 700 1.1rem 'Space Grotesk', sans-serif; text-decoration: none; }
        .brand i { display: grid; place-items: center; width: 34px; height: 34px; border-radius: 10px 10px 10px 3px; background: var(--mint); }
        .kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px; color: var(--green); font-size: .72rem; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
        .kicker::before { width: 22px; height: 2px; background: #f27864; content: ''; }
        h1 { margin: 0 0 8px; font: 700 2rem 'Space Grotesk', sans-serif; letter-spacing: -.04em; }
        .intro { margin: 0 0 26px; color: var(--muted); line-height: 1.6; }
        .label { display: block; margin: 18px 0 8px; font-size: .82rem; font-weight: 700; }
        .input { width: 100%; padding: 14px; border: 1px solid var(--line); border-radius: 9px; outline: 0; font: inherit; }
        .input:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
        .error { color: #c64c3d; font-size: .78rem; }
        .notice { margin-bottom: 18px; padding: 12px 14px; border-radius: 8px; color: var(--green); background: #eaf8f2; font-size: .82rem; }
        .submit { width: 100%; margin-top: 25px; padding: 14px; border: 0; border-radius: 9px; color: #fff; background: var(--green); cursor: pointer; font: 700 .9rem 'DM Sans', sans-serif; }
        .back { display: block; margin-top: 20px; color: var(--green); font-size: .82rem; font-weight: 700; text-align: center; text-decoration: none; }
        @media (max-width: 480px) { .auth-card { padding: 28px 22px; } }
    </style>
</head>
<body>
    <main class="auth-card">
        <a class="brand" href="{{ route('home') }}"><i class="bi bi-briefcase-fill" aria-hidden="true"></i> JobPortal</a>
        @if(session('success'))<div class="notice">{{ session('success') }}</div>@endif
        <div class="kicker">Account recovery</div>
        <h1>Reset your password</h1>
        <p class="intro">Enter your email address and we will send you a secure password reset link.</p>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label class="label" for="email">Email address</label>
            <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
            @error('email')<p class="error">{{ $message }}</p>@enderror
            <button class="submit" type="submit">Continue <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
        </form>
        <a class="back" href="{{ route('login') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to login</a>
    </main>
</body>
</html>
