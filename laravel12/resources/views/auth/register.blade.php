<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account | Job Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --ink: #18232d; --muted: #6d7a80; --line: #dce5e4; --mint: #ccefe3; --green: #087f67; --coral: #f27864; --cream: #f7f8f2; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; overflow-x: hidden; color: var(--ink); background: var(--cream); font-family: 'DM Sans', sans-serif; }
        .register-shell { display: grid; grid-template-columns: minmax(340px, .9fr) minmax(520px, 1.1fr); min-height: 100vh; min-height: 100dvh; }
        .story { position: relative; overflow: hidden; padding: 42px clamp(32px, 6vw, 92px); background: var(--ink); color: #fff; display: flex; flex-direction: column; justify-content: space-between; }
        .story::before, .story::after { content: ''; position: absolute; border: 1px solid rgba(204, 239, 227, .22); border-radius: 50%; }
        .story::before { width: 430px; height: 430px; right: -220px; top: 15%; }
        .story::after { width: 590px; height: 590px; left: -390px; bottom: -270px; }
        .brand { position: relative; z-index: 1; display: flex; align-items: center; gap: 10px; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.1rem; letter-spacing: .02em; }
        .brand-mark { display: grid; place-items: center; width: 34px; height: 34px; color: var(--ink); background: var(--mint); border-radius: 10px 10px 10px 3px; font-size: .9rem; }
        .story-copy { position: relative; z-index: 1; max-width: 470px; margin: auto 0; }
        .eyebrow { color: var(--mint); text-transform: uppercase; letter-spacing: .16em; font-size: .72rem; font-weight: 700; }
        h1, h2 { font-family: 'Space Grotesk', sans-serif; letter-spacing: -.04em; }
        h1 { max-width: 500px; margin: 18px 0; font-size: clamp(2.8rem, 5vw, 5rem); line-height: .98; }
        .story-copy > p { max-width: 390px; color: #b7c6c8; font-size: 1.05rem; line-height: 1.65; }
        .quote { position: relative; z-index: 1; max-width: 390px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,.18); color: #dbe7e4; font-size: .9rem; line-height: 1.6; }
        .quote strong { display: block; margin-top: 12px; color: #fff; font-size: .78rem; }
        .form-side { display: grid; place-items: center; padding: 48px clamp(24px, 7vw, 112px); }
        .form-wrap { width: min(100%, 520px); margin-top: 20px; }
        .form-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 14px; color: var(--green); font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .form-kicker::before { content: ''; width: 22px; height: 2px; background: var(--coral); }
        .form-heading { margin-bottom: 30px; }
        h2 { margin: 0 0 8px; font-size: 2.15rem; }
        .form-heading p { margin: 0; color: var(--muted); }
        .field-label { display: block; margin: 20px 0 8px; font-size: .82rem; font-weight: 700; }
        .input { width: 100%; border: 1px solid var(--line); border-radius: 10px; padding: 15px 16px; outline: 0; color: var(--ink); background: #fff; font: inherit; transition: border-color .2s, box-shadow .2s; }
        .input:focus { border-color: var(--green); box-shadow: 0 0 0 4px rgba(8,127,103,.1); }
        .input.is-invalid { border-color: var(--coral); }
        .error { margin: 7px 0 0; color: #c64c3d; font-size: .78rem; }
        .role-label { display: block; margin-bottom: 10px; font-size: .82rem; font-weight: 700; }
        .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .role-card { position: relative; display: block; min-height: 116px; padding: 18px; border: 1px solid var(--line); border-radius: 12px; background: #fff; cursor: pointer; }
        .role-card:focus-within { outline: 3px solid rgba(8,127,103,.18); outline-offset: 2px; }
        .role-card input { position: absolute; opacity: 0; }
        .role-card:has(input:checked) { border-color: var(--green); background: var(--mint); box-shadow: none; }
        .role-icon { display: grid; place-items: center; width: 32px; height: 32px; margin-bottom: 12px; border-radius: 9px; color: #fff; background: var(--green); font-size: .9rem; }
        .role-card:last-child .role-icon { background: var(--coral); }
        .role-title { display: block; font-weight: 700; }
        .role-copy { display: block; margin-top: 3px; color: var(--muted); font-size: .76rem; }
        .password-note { display: block; margin-top: 7px; color: var(--muted); font-size: .75rem; }
        .submit { width: 100%; margin-top: 36px; padding: 16px; border: 0; border-radius: 10px; color: #fff; background: var(--green); cursor: pointer; font: 700 .95rem 'DM Sans', sans-serif; transition: background .2s; }
        .submit:hover { background: #05634f; }
        .submit .fa-arrow-right { margin-left: 8px; transition: transform .2s ease; }
        .submit:hover .fa-arrow-right { transform: translateX(6px); }
        .login-link { margin: 20px 0 0; text-align: center; color: var(--muted); font-size: .88rem; }
        .form-trust { display: flex; justify-content: center; gap: 18px; margin: 18px 0 0; color: var(--muted); font-size: .72rem; }
        .form-trust span { display: inline-flex; align-items: center; gap: 5px; }
        .form-trust i { color: var(--green); font-size: .7rem; }
        a { color: var(--green); font-weight: 700; text-decoration: none; }
        a:hover { text-decoration: underline; }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; } }
        @media (min-width: 821px) and (max-height: 850px) {
            .story { padding-top: 28px; padding-bottom: 28px; }
            .form-side { padding-top: 24px; padding-bottom: 24px; }
            .form-kicker { margin-bottom: 9px; }
            .form-heading { margin-bottom: 20px; }
            h2 { font-size: 1.9rem; }
            .field-label { margin-top: 13px; margin-bottom: 6px; }
            .input { padding-top: 12px; padding-bottom: 12px; }
            .role-card { min-height: 98px; padding: 14px; }
            .role-icon { margin-bottom: 8px; }
            .submit { margin-top: 20px; padding-top: 13px; padding-bottom: 13px; }
            .login-link { margin-top: 12px; }
            .form-trust { margin-top: 12px; }
        }
        @media (min-width: 821px) and (max-height: 720px) {
            .story { padding-top: 18px; padding-bottom: 18px; }
            .form-side { padding: 10px clamp(24px, 5vw, 64px); }
            .form-wrap { margin-top: 10px; }
            .form-kicker { margin-bottom: 4px; }
            .form-heading { margin-bottom: 8px; }
            h2 { font-size: 1.65rem; }
            .field-label { margin-top: 7px; margin-bottom: 4px; }
            .input { padding-top: 9px; padding-bottom: 9px; }
            .role-grid { gap: 8px; }
            .role-card { min-height: 76px; padding: 10px; }
            .role-icon { width: 26px; height: 26px; margin-bottom: 4px; }
            .role-copy { font-size: .7rem; }
            .password-note { margin-top: 3px; }
            .submit { margin-top: 18px; padding-top: 10px; padding-bottom: 10px; }
            .login-link { margin-top: 7px; }
            .form-trust { margin-top: 6px; }
        }
        @media (max-width: 820px) { .register-shell { grid-template-columns: 1fr; } .story { min-height: 360px; padding: 28px 24px; } .story-copy { margin: 52px 0 20px; } h1 { font-size: clamp(2.7rem, 12vw, 4rem); } .quote { display: none; } .form-side { padding: 42px 24px 56px; } }
        @media (max-width: 420px) { .role-grid { grid-template-columns: 1fr; } .role-card { min-height: 94px; } }
    </style>
</head>
<body>
    <main class="register-shell">
        <section class="story" aria-label="Job Portal introduction">
            <div class="brand"><span class="brand-mark"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></span> Job Portal</div>
            <div class="story-copy">
                <span class="eyebrow">Your next chapter starts here</span>
                <h1>Make work<br>worth finding.</h1>
                <p>Build a profile that opens doors, or bring the right people onto your team.</p>
            </div>
            <div class="quote">“The right opportunity can change more than your job. It can change your direction.”<strong>Job Portal community</strong></div>
        </section>

        <section class="form-side">
            <div class="form-wrap">
                <div class="form-heading">
                    <span class="form-kicker">Join the community</span>
                    <h2>Create your account</h2>
                    <p>It takes less than a minute to get started.</p>
                </div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <span class="role-label">What brings you here?</span>
                    <div class="role-grid">
                        <label class="role-card">
                            <input type="radio" name="role" value="job_seeker" {{ old('role', request('role')) == 'job_seeker' ? 'checked' : '' }} required>
                            <span class="role-icon"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></span><span class="role-title">I’m looking for work</span><span class="role-copy">Discover your next opportunity</span>
                        </label>
                        <label class="role-card">
                            <input type="radio" name="role" value="employer" {{ old('role', request('role')) == 'employer' ? 'checked' : '' }} required>
                            <span class="role-icon"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></span><span class="role-title">I’m hiring talent</span><span class="role-copy">Find people who fit your team</span>
                        </label>
                    </div>
                    @error('role')<p class="error">{{ $message }}</p>@enderror

                    <label class="field-label" for="name">Full name</label>
                    <input class="input @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="e.g. Ayesha Khan" autocomplete="name" required>
                    @error('name')<p class="error">{{ $message }}</p>@enderror

                    <label class="field-label" for="email">Email address</label>
                    <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" inputmode="email" autocapitalize="none" maxlength="255" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}" title="Enter a valid email address, such as you@example.com" required>
                    @error('email')<p class="error">{{ $message }}</p>@enderror

                    <label class="field-label" for="password">Create a password</label>
                    <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password" required>
                    <span class="password-note">Use at least 6 characters.</span>
                    @error('password')<p class="error">{{ $message }}</p>@enderror

                    <label class="field-label" for="password_confirmation">Confirm password</label>
                    <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>

                    <button class="submit" type="submit">Create my account <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
                    <p class="login-link">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                    <div class="form-trust" aria-label="Account benefits"><span><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Free to join</span><span><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Quick setup</span></div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
