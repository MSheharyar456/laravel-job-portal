@extends('layouts.app')

@section('title', 'My Profile - Job Seeker')

@push('styles')
<style>
    .profile-page { --profile-ink: #18232d; --profile-muted: #6d7a80; --profile-line: #dce5e4; --profile-teal: #087f67; --profile-mint: #ccefe3; max-width: 1120px; padding: 18px 0 54px; color: var(--profile-ink); }
    .profile-header { margin-bottom: 27px; }
    .profile-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 9px; color: var(--profile-teal); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .profile-kicker::before { width: 22px; height: 2px; background: #f27864; content: ''; }
    .profile-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .profile-header p { margin: 8px 0 0; color: var(--profile-muted); }
    .profile-page .card { border: 1px solid var(--profile-line); border-radius: 12px; box-shadow: none; transition: none; }
    .profile-page .card:hover { box-shadow: none; transform: none; }
    .profile-page .card-header { border-bottom: 1px solid var(--profile-line); border-radius: 12px 12px 0 0 !important; color: var(--profile-ink); background: #f7faf8; font-family: 'Space Grotesk', 'DM Sans', sans-serif; }
    .profile-page .form-label { color: var(--profile-ink); font-size: .82rem; font-weight: 700; }
    .profile-page .form-control { border: 1px solid var(--profile-line); border-radius: 8px; font-size: .86rem; }
    .profile-page .form-control:focus { border-color: var(--profile-teal); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .profile-page .btn-primary { border: 0; border-radius: 8px; background: var(--profile-teal); font-weight: 700; }
    .profile-page .btn-primary:hover { background: #05634f; }
    .profile-save { position: sticky; top: 20px; }
    .profile-save .card-body { padding: 24px; }
    .profile-tip { margin-top: 14px; color: var(--profile-muted); font-size: .78rem; line-height: 1.6; }
    .profile-page .resume-limit { display: block; margin-top: 7px; color: var(--profile-teal); font-size: .78rem; font-weight: 700; }
    .profile-page .current-resume { display: inline-flex; align-items: center; gap: 7px; color: var(--profile-teal); font-size: .8rem; font-weight: 700; text-decoration: none; }
    .profile-page .current-resume:hover { text-decoration: underline; }
    .profile-notice { position: fixed; top: 22px; right: 22px; z-index: 1100; display: flex; align-items: center; gap: 9px; width: min(360px, calc(100vw - 44px)); padding: 14px 17px; border: 1px solid #b9e7d5; border-radius: 10px; color: #087f67; background: #eaf8f2; box-shadow: 0 12px 30px rgba(24,35,45,.16); font-size: .84rem; font-weight: 700; opacity: 0; transform: translateX(calc(100% + 22px)); pointer-events: none; transition: opacity .25s ease, transform .35s cubic-bezier(.22,1,.36,1); }
    .profile-notice.is-visible { opacity: 1; transform: translateX(0); }
    .profile-notice.is-error { border-color: #f2c2bb; color: #c64c3d; background: #fff0ed; }
    .profile-save .btn:disabled { opacity: .6; cursor: wait; }
    @media (max-width: 767px) { .profile-page { padding: 8px 0 42px; } .profile-save { position: static; } .profile-notice { top: 14px; right: 14px; width: calc(100vw - 28px); } }
</style>
@endpush

@section('content')
<div class="container profile-page">
    <div id="profile-notice" class="profile-notice" role="status"><i class="bi bi-check-circle" aria-hidden="true"></i><span></span></div>
    <div class="profile-header"><span class="profile-kicker">Job seeker workspace</span><h1>My profile</h1><p>Keep your professional details current so employers can understand your experience.</p></div>
    <form id="profile-form" method="POST" action="{{ route('job-seeker.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">Personal information</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled></div>
                        <div class="col-md-6"><label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}"></div>
                        <div class="col-md-6"><label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" value="{{ auth()->user()->location }}"></div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">Professional Details</h5></div>
                    <div class="card-body">
                        <div class="mb-3"><label class="form-label">Skills</label>
                            <textarea name="skills" class="form-control" rows="3" placeholder="Laravel, PHP, JavaScript...">{{ $profile->skills ?? '' }}</textarea></div>
                        <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Experience</label>
                            <textarea name="experience" class="form-control" rows="4">{{ $profile->experience ?? '' }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Education</label>
                            <textarea name="education" class="form-control" rows="4">{{ $profile->education ?? '' }}</textarea></div>
                        </div>
                        <div class="mt-3"><label class="form-label" for="resume">Resume / CV</label>
                            <input id="resume" type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                            <small class="resume-limit">Maximum file size: 5 MB</small>
                            @if($profile && $profile->resume_path)<p class="mt-2 mb-0"><a class="current-resume" href="{{ Storage::url($profile->resume_path) }}" target="_blank"><i class="bi bi-file-earmark-text"></i> View current resume</a></p>@endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card profile-save"><div class="card-body text-center">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Profile</button>
                    <p class="profile-tip">Your profile helps employers quickly understand your skills, experience, and education.</p>
                    </div></div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('profile-form');
    const notice = document.getElementById('profile-notice');
    const button = form.querySelector('button[type="submit"]');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        button.disabled = true;
        notice.classList.remove('is-visible', 'is-error');
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || 'Unable to update profile.');
            notice.querySelector('i').className = 'bi bi-check-circle';
            notice.querySelector('span').textContent = payload.message;
            notice.classList.add('is-visible');
            button.innerHTML = '<i class="bi bi-check-circle"></i> Saved';
            window.setTimeout(() => notice.classList.remove('is-visible'), 4500);
        } catch (error) {
            notice.querySelector('i').className = 'bi bi-exclamation-triangle';
            notice.querySelector('span').textContent = error.message;
            notice.classList.add('is-visible', 'is-error');
            button.disabled = false;
        }
    });
})();
</script>
@endpush