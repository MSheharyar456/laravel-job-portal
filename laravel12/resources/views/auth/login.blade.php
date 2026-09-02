<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Job Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --ink: #18232d; --muted: #6d7a80; --line: #dce5e4; --mint: #ccefe3; --green: #087f67; --coral: #f27864; --cream: #f7f8f2; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; overflow-x: hidden; color: var(--ink); background: var(--cream); font-family: 'DM Sans', sans-serif; }
        .login-shell { display: grid; grid-template-columns: minmax(340px, .9fr) minmax(520px, 1.1fr); min-height: 100vh; min-height: 100dvh; }
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
        .form-wrap { width: min(100%, 470px); margin-top: 20px; }
        .home-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 28px; color: var(--muted); font-size: .8rem; font-weight: 700; }
        .home-link i { color: var(--green); font-size: .75rem; }
        .home-link:hover { color: var(--green); text-decoration: none; }
        .form-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 14px; color: var(--green); font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .form-kicker::before { content: ''; width: 22px; height: 2px; background: var(--coral); }
        .form-heading { margin-bottom: 30px; }
        h2 { margin: 0 0 8px; font-size: 2.15rem; }
        .form-heading p { margin: 0; color: var(--muted); }
        .field-label { display: block; margin: 20px 0 8px; font-size: .82rem; font-weight: 700; }
        .input { width: 100%; border: 1px solid var(--line); border-radius: 10px; padding: 15px 16px; outline: 0; color: var(--ink); background: #fff; font: inherit; transition: border-color .2s, box-shadow .2s; }
        .password-field { position: relative; }
        .password-field .input { padding-right: 48px; }
        .password-toggle { position: absolute; top: 50%; right: 8px; display: grid; place-items: center; width: 36px; height: 36px; border: 0; border-radius: 8px; color: var(--muted); background: transparent; cursor: pointer; transform: translateY(-50%); }
        .password-toggle:hover, .password-toggle:focus-visible { color: var(--green); background: rgba(8,127,103,.08); outline: 0; }
        .input:focus { border-color: var(--green); box-shadow: 0 0 0 4px rgba(8,127,103,.1); }
        .input.is-invalid { border-color: var(--coral); }
        .error { margin: 7px 0 0; color: #c64c3d; font-size: .78rem; }
        .remember-row { display: flex; align-items: center; gap: 9px; margin-top: 18px; color: var(--muted); font-size: .82rem; }
        .remember-row input { width: 16px; height: 16px; accent-color: var(--green); }
        .remember-row label { cursor: pointer; }
        .submit { width: 100%; margin-top: 34px; padding: 16px; border: 0; border-radius: 10px; color: #fff; background: var(--green); cursor: pointer; font: 700 .95rem 'DM Sans', sans-serif; transition: background .2s; }
        .submit:hover { background: #05634f; }
        .submit .fa-arrow-right { margin-left: 8px; transition: transform .2s ease; }
        .submit:hover .fa-arrow-right { transform: translateX(6px); }
        .register-link { margin: 20px 0 0; text-align: center; color: var(--muted); font-size: .88rem; }
        a { color: var(--green); font-weight: 700; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .form-trust { display: flex; justify-content: center; gap: 18px; margin: 18px 0 0; color: var(--muted); font-size: .72rem; }
        .form-trust span { display: inline-flex; align-items: center; gap: 5px; }
        .form-trust i { color: var(--green); font-size: .7rem; }
        .reset-success { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 22px; padding: 13px 15px; border: 1px solid #a9dfcc; border-radius: 10px; color: #155b4b; background: #effbf6; font-size: .84rem; line-height: 1.45; }
        .reset-success i { margin-top: 2px; color: var(--green); }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; } }
        @media (min-width: 821px) and (max-height: 850px) {
            .story { padding-top: 28px; padding-bottom: 28px; }
            .form-side { padding-top: 24px; padding-bottom: 24px; }
            .form-wrap { margin-top: 10px; }
            .home-link { margin-bottom: 16px; }
            .form-kicker { margin-bottom: 9px; }
            .form-heading { margin-bottom: 20px; }
            h2 { font-size: 1.9rem; }
            .field-label { margin-top: 13px; margin-bottom: 6px; }
            .input { padding-top: 12px; padding-bottom: 12px; }
            .submit { margin-top: 22px; padding-top: 13px; padding-bottom: 13px; }
            .register-link { margin-top: 12px; }
            .form-trust { margin-top: 12px; }
        }
        @media (min-width: 821px) and (max-height: 720px) {
            .story { padding-top: 18px; padding-bottom: 18px; }
            .form-side { padding: 10px clamp(24px, 5vw, 64px); }
            .form-wrap { margin-top: 8px; }
            .home-link { margin-bottom: 8px; }
            .form-kicker { margin-bottom: 4px; }
            .form-heading { margin-bottom: 8px; }
            h2 { font-size: 1.65rem; }
            .field-label { margin-top: 7px; margin-bottom: 4px; }
            .input { padding-top: 9px; padding-bottom: 9px; }
            .remember-row { margin-top: 9px; }
            .submit { margin-top: 14px; padding-top: 10px; padding-bottom: 10px; }
            .register-link { margin-top: 7px; }
            .form-trust { margin-top: 6px; }
        }
        @media (max-width: 820px) { .login-shell { grid-template-columns: 1fr; } .story { min-height: 360px; padding: 28px 24px; } .story-copy { margin: 52px 0 20px; } h1 { font-size: clamp(2.7rem, 12vw, 4rem); } .quote { display: none; } .form-side { padding: 42px 24px 56px; } }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="story" aria-label="Job Portal introduction">
            <div class="brand"><span class="brand-mark"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></span> Job Portal</div>
            <div class="story-copy">
                <span class="eyebrow">Good to see you again</span>
                <h1>Pick up<br>where you left off.</h1>
                <p>Your next opportunity is still out there. Sign in and keep moving forward.</p>
            </div>
            <div class="quote">“Small steps every day add up to big changes.”<strong>Job Portal community</strong></div>
        </section>

        <section class="form-side">
            <div class="form-wrap">
                <a class="home-link" href="{{ route('home') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Go to home</a>
                @if (session('success'))
                    <div class="reset-success" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>{{ session('success') }}</span></div>
                @endif
                <div class="form-heading">
                    <span class="form-kicker">Welcome back</span>
                    <h2>Log in to your account</h2>
                    <p>Enter your details to continue.</p>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <label class="field-label" for="email">Email address</label>
                    <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" inputmode="email" autocapitalize="none" maxlength="255" pattern="[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}" title="Enter a valid email address, such as you@example.com" required autofocus>
                    @error('email')<p class="error">{{ $message }}</p>@enderror

                    <label class="field-label" for="password">Password</label>
                    <div class="password-field"><input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="current-password" required><button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false"><i class="fa-regular fa-eye" aria-hidden="true"></i></button></div>
                    @error('password')<p class="error">{{ $message }}</p>@enderror

                    <div class="remember-row"><input id="remember" name="remember" type="checkbox"><label for="remember">Remember me</label></div>
                    <p style="margin: 12px 0 0; text-align: right; font-size: .78rem;"><a href="{{ route('password.request') }}">Forgot password?</a></p>
                    <button class="submit" type="submit">Log in <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
                    <p class="register-link">Don’t have an account? <a href="{{ route('register') }}">Create one</a></p>
                    <div class="form-trust" aria-label="Account benefits"><span><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Secure access</span><span><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Quick login</span></div>
                </form>
            </div>
        </section>
    </main>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                button.setAttribute('aria-pressed', String(isHidden));
                button.querySelector('i').className = isHidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
            });
        });
    </script>
</body>
</html>
