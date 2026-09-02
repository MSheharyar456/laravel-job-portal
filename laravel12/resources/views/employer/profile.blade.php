@extends('layouts.app')

@section('title', 'Company Profile')

@push('styles')
<style>
    .profile-page { --profile-ink: #18232d; --profile-muted: #6d7a80; --profile-line: #dce5e4; --profile-mint: #ccefe3; --profile-green: #087f67; --profile-coral: #f27864; padding: 42px 0 64px; color: var(--profile-ink); }
    .profile-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 28px; }
    .profile-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: var(--profile-green); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .profile-kicker::before { content: ''; width: 22px; height: 2px; background: var(--profile-coral); }
    .profile-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .profile-header p { margin: 8px 0 0; color: var(--profile-muted); }
    .profile-form { border: 1px solid var(--profile-line); border-radius: 12px; background: #fff; overflow: hidden; }
    .form-section { padding: 28px clamp(20px, 4vw, 38px); border-bottom: 1px solid var(--profile-line); }
    .form-section:last-of-type { border-bottom: 0; }
    .section-title { display: flex; align-items: flex-start; gap: 13px; margin-bottom: 22px; }
    .section-icon { display: grid; place-items: center; flex: 0 0 34px; height: 34px; border-radius: 9px; color: var(--profile-green); background: var(--profile-mint); }
    .section-title h2 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.15rem; letter-spacing: -.03em; }
    .section-title p { margin: 3px 0 0; color: var(--profile-muted); font-size: .8rem; }
    .profile-label { display: block; margin: 0 0 8px; color: var(--profile-ink); font-size: .8rem; font-weight: 700; }
    .profile-input, .profile-textarea { width: 100%; border: 1px solid var(--profile-line); border-radius: 9px; padding: 13px 14px; outline: 0; color: var(--profile-ink); background: #fff; font: inherit; font-size: .88rem; transition: border-color .2s, box-shadow .2s; }
    .profile-input:focus, .profile-textarea:focus { border-color: var(--profile-green); box-shadow: 0 0 0 4px rgba(8,127,103,.1); }
    .profile-textarea { min-height: 160px; resize: vertical; }
    .profile-input.is-invalid { border-color: var(--profile-coral); }
    .field-group { margin-bottom: 20px; }
    .field-group:last-child { margin-bottom: 0; }
    .field-error { margin: 7px 0 0; color: #c64c3d; font-size: .76rem; }
    .logo-panel { display: flex; align-items: center; gap: 18px; padding: 16px 18px; border: 1px solid var(--profile-line); border-radius: 10px; background: #f7faf8; }
    .logo-thumb { display: grid; place-items: center; width: 64px; height: 64px; border-radius: 14px; overflow: hidden; background: var(--profile-mint); color: var(--profile-green); }
    .logo-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .logo-caption { color: var(--profile-muted); font-size: .76rem; }
    .form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding: 20px clamp(20px, 4vw, 38px); background: #f7faf8; }
    .cancel-link { padding: 12px 16px; color: var(--profile-muted); font-size: .84rem; font-weight: 700; text-decoration: none; }
    .cancel-link:hover { color: var(--profile-green); }
    .save-button { display: inline-flex; align-items: center; gap: 9px; border: 0; border-radius: 9px; padding: 13px 18px; color: #fff; background: var(--profile-green); cursor: pointer; font: 700 .86rem 'DM Sans', sans-serif; }
    .save-button:hover { color: #fff; background: #05634f; }
    @media (max-width: 620px) { .profile-page { padding: 28px 0 44px; } .profile-header { align-items: flex-start; flex-direction: column; } .logo-panel { flex-direction: column; align-items: flex-start; } .form-actions { align-items: stretch; flex-direction: column-reverse; } .save-button { justify-content: center; width: 100%; } }
</style>
@endpush

@section('content')
<div class="container profile-page">
    <div class="profile-header">
        <div>
            <span class="profile-kicker">Employer workspace</span>
            <h1>Company profile</h1>
            <p>Share the details employers and candidates look for before they connect.</p>
        </div>
    </div>

    <form class="profile-form" method="POST" action="{{ route('employer.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <section class="form-section">
            <div class="section-title">
                <span class="section-icon"><i class="bi bi-building" aria-hidden="true"></i></span>
                <div>
                    <h2>Company basics</h2>
                    <p>Tell candidates who you are and what your business stands for.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6 field-group mb-0">
                    <label class="profile-label" for="company_name">Company name</label>
                    <input class="profile-input @error('company_name') is-invalid @enderror" id="company_name" name="company_name" type="text" value="{{ old('company_name', $employer->company_name ?? '') }}" required>
                    @error('company_name')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="col-md-6 field-group mb-0">
                    <label class="profile-label" for="industry">Industry</label>
                    <input class="profile-input @error('industry') is-invalid @enderror" id="industry" name="industry" type="text" value="{{ old('industry', $employer->industry ?? '') }}" placeholder="e.g. Technology, Finance, Healthcare">
                    @error('industry')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="field-group mt-3 mb-0">
                <label class="profile-label" for="company_description">Company description</label>
                <textarea class="profile-textarea @error('company_description') is-invalid @enderror" id="company_description" name="company_description" placeholder="Share your mission, culture, and the kind of talent you hire.">{{ old('company_description', $employer->company_description ?? '') }}</textarea>
                @error('company_description')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </section>

        <section class="form-section">
            <div class="section-title">
                <span class="section-icon"><i class="bi bi-globe" aria-hidden="true"></i></span>
                <div>
                    <h2>Contact details</h2>
                    <p>Give candidates the right way to learn more about your company.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6 field-group mb-0">
                    <label class="profile-label" for="website">Website</label>
                    <input class="profile-input @error('website') is-invalid @enderror" id="website" name="website" type="url" value="{{ old('website', $employer->website ?? '') }}" placeholder="https://example.com">
                    @error('website')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="col-md-6 field-group mb-0">
                    <label class="profile-label" for="phone">Phone number</label>
                    <input class="profile-input @error('phone') is-invalid @enderror" id="phone" name="phone" type="text" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="+92 300 0000000">
                    @error('phone')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="form-section">
            <div class="section-title">
                <span class="section-icon"><i class="bi bi-image" aria-hidden="true"></i></span>
                <div>
                    <h2>Brand identity</h2>
                    <p>Upload a logo to make your company stand out in job listings.</p>
                </div>
            </div>

            <div class="field-group mb-0">
                <label class="profile-label" for="company_logo">Company logo</label>
                <div class="logo-panel">
                    <div class="logo-thumb">
                        @if(!empty($employer->company_logo))
                            <img src="{{ Storage::disk('public')->url($employer->company_logo) }}" alt="Company logo preview">
                        @else
                            <i class="bi bi-building fs-4" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div>
                        <input class="profile-input @error('company_logo') is-invalid @enderror" id="company_logo" name="company_logo" type="file" accept="image/*">
                        <div class="logo-caption">PNG, JPG, WEBP up to 2MB</div>
                        @error('company_logo')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </section>

        <div class="form-actions">
            <a class="cancel-link" href="{{ route('employer.dashboard') }}">Cancel</a>
            <button class="save-button" type="submit"><i class="bi bi-save" aria-hidden="true"></i> Save profile</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.querySelector('.profile-form')?.addEventListener('submit', (event) => {
    const form = event.currentTarget;
    if (form.dataset.submitting === 'true') {
        event.preventDefault();
        return;
    }
    form.dataset.submitting = 'true';
    form.querySelectorAll('button[type="submit"]').forEach((button) => {
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
    });
});
</script>
@endpush