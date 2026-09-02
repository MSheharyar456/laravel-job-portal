<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set new password | Job Portal</title>
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
        .intro { margin: 0 0 25px; color: var(--muted); }
        .label { display: block; margin: 18px 0 8px; font-size: .82rem; font-weight: 700; }
        .input { width: 100%; padding: 14px; border: 1px solid var(--line); border-radius: 9px; outline: 0; font: inherit; }
        .input:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
        .error { color: #c64c3d; font-size: .78rem; }
        .submit { width: 100%; margin-top: 25px; padding: 14px; border: 0; border-radius: 9px; color: #fff; background: var(--green); cursor: pointer; font: 700 .9rem 'DM Sans', sans-serif; }
        @media (max-width: 480px) { .auth-card { padding: 28px 22px; } }
    </style>
</head>
<body>
    <main class="auth-card">
        <a class="brand" href="{{ route('home') }}"><i class="bi bi-briefcase-fill" aria-hidden="true"></i> JobPortal</a>
        <div class="kicker">Account recovery</div>
        <h1>Set a new password</h1>
        <p class="intro">Choose and confirm a new password for your account.</p>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label class="label" for="email">Email address</label>
            <input class="input" id="email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" required>
            @error('email')<p class="error">{{ $message }}</p>@enderror
            <label class="label" for="password">New password</label>
            <input class="input" id="password" name="password" type="password" autocomplete="new-password" required>
            @error('password')<p class="error">{{ $message }}</p>@enderror
            <label class="label" for="password_confirmation">Confirm new password</label>
            <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
            <button class="submit" type="submit">Reset password <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
        </form>
    </main>
</body>
</html>
