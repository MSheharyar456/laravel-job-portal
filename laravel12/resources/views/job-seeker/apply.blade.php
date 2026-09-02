@extends('layouts.app')

@section('title', 'Apply for Job')

@push('styles')
<style>
    .apply-page { --apply-ink: #18232d; --apply-muted: #6d7a80; --apply-line: #dce5e4; --apply-teal: #087f67; --apply-mint: #ccefe3; max-width: 980px; padding: 18px 0 54px; color: var(--apply-ink); }
    .apply-header { margin-bottom: 25px; }
    .apply-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 9px; color: var(--apply-teal); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .apply-kicker::before { width: 22px; height: 2px; background: #f27864; content: ''; }
    .apply-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .apply-header p { margin: 8px 0 0; color: var(--apply-muted); }
    .apply-page .card { border: 1px solid var(--apply-line); border-radius: 12px; box-shadow: none; transition: none; }
    .apply-page .card:hover { box-shadow: none; transform: none; }
    .apply-summary { height: 100%; padding: 25px; background: #f7faf8; }
    .apply-summary h2 { margin: 0 0 8px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.35rem; }
    .apply-summary p { color: var(--apply-muted); font-size: .86rem; line-height: 1.7; }
    .apply-summary-meta { display: grid; gap: 12px; margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--apply-line); color: var(--apply-muted); font-size: .82rem; }
    .apply-summary-meta i { width: 18px; color: var(--apply-teal); }
    .apply-form-card .card-body { padding: clamp(24px, 4vw, 38px); }
    .apply-form-card h2 { margin: 0 0 23px; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.35rem; }
    .apply-page .form-label { color: var(--apply-ink); font-size: .82rem; font-weight: 700; }
    .apply-page .form-control { border: 1px solid var(--apply-line); border-radius: 8px; font-size: .86rem; }
    .apply-page .form-control:focus { border-color: var(--apply-teal); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .apply-page textarea { min-height: 165px; resize: vertical; }
    .resume-note { display: flex; align-items: flex-start; gap: 9px; margin-top: 8px; color: var(--apply-muted); font-size: .76rem; line-height: 1.5; }
    .resume-limit { display: block; margin-top: 4px; color: var(--apply-teal); font-size: .78rem; font-weight: 700; }
    .resume-note i { color: var(--apply-teal); }
    .apply-actions { display: flex; align-items: center; gap: 10px; margin-top: 25px; }
    .apply-actions .btn { border-radius: 8px; padding: 11px 16px; font-size: .82rem; font-weight: 700; }
    .apply-actions .btn-primary { border: 0; background: var(--apply-teal); }
    .apply-actions .btn-primary:hover { background: #05634f; }
    .apply-actions .btn-outline-secondary { border-color: var(--apply-line); color: var(--apply-muted); }
    .application-notice { position: fixed; top: 22px; right: 22px; z-index: 1100; display: flex; align-items: center; gap: 9px; width: min(390px, calc(100vw - 44px)); padding: 14px 17px; border: 1px solid #b9e7d5; border-radius: 10px; color: #087f67; background: #eaf8f2; box-shadow: 0 12px 30px rgba(24,35,45,.16); font-size: .84rem; font-weight: 700; opacity: 0; transform: translateX(calc(100% + 22px)); pointer-events: none; transition: opacity .25s ease, transform .35s cubic-bezier(.22,1,.36,1); }
    .application-notice.is-visible { opacity: 1; transform: translateX(0); }
    .application-notice.is-error { border-color: #f2c2bb; color: #c64c3d; background: #fff0ed; }
    .apply-actions .btn:disabled { opacity: .6; cursor: wait; }
    @media (max-width: 767px) { .apply-page { padding: 8px 0 42px; } .apply-summary { height: auto; } .apply-actions { align-items: stretch; flex-direction: column; } .apply-actions .btn { width: 100%; } .application-notice { top: 14px; right: 14px; width: calc(100vw - 28px); } }
</style>
@endpush

@section('content')
<div class="container apply-page">
    <div id="application-notice" class="application-notice" role="status"><i class="bi bi-check-circle" aria-hidden="true"></i><span></span></div>
    <div class="apply-header"><span class="apply-kicker">Application workspace</span><h1>Apply for this role</h1><p>Share your experience and give the employer a clear reason to meet you.</p></div>
    <div class="row g-4">
        <div class="col-md-5">
            <div class="card apply-summary">
                <h2>{{ $job->title }}</h2>
                <p>{{ Str::limit($job->description, 250) }}</p>
                <div class="apply-summary-meta">
                    <span><i class="bi bi-building"></i>{{ $job->employer->user->name ?? 'Company Name' }}</span>
                    <span><i class="bi bi-geo-alt-fill"></i>{{ $job->location }}</span>
                    <span><i class="bi bi-briefcase"></i>{{ ucfirst($job->job_type) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card apply-form-card">
                <div class="card-body">
                    <h2>Your application</h2>
                    <form id="application-form" method="POST" action="{{ route('job-seeker.jobs.apply.submit', $job->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4"><label class="form-label" for="cover_letter">Cover letter</label><textarea id="cover_letter" name="cover_letter" class="form-control" required placeholder="Tell the employer why you're a great fit..."></textarea></div>
                        <div class="mb-0"><label class="form-label" for="resume">Resume / CV</label><input id="resume" type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" {{ $profile && $profile->resume_path ? '' : 'required' }}><span class="resume-limit">Maximum file size: 5 MB</span><small class="resume-note"><i class="bi bi-info-circle"></i><span>{{ $profile && $profile->resume_path ? 'Your profile resume will be used if you do not upload a new file.' : 'A CV is required. Upload a PDF, DOC, or DOCX resume.' }}</span></small></div>
                        <div class="apply-actions"><button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Submit application</button><a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-secondary">Cancel</a></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('application-form');
    const notice = document.getElementById('application-notice');
    const submitButton = form.querySelector('button[type="submit"]');
    let isSubmitting = false;
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (isSubmitting) return;
        isSubmitting = true;
        submitButton.disabled = true;
        notice.classList.remove('is-visible', 'is-error');
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || 'Unable to submit application.');
            notice.querySelector('i').className = 'bi bi-check-circle';
            notice.querySelector('span').textContent = payload.message;
            notice.classList.add('is-visible');
            form.reset();
            submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i> Application submitted';
        } catch (error) {
            notice.querySelector('i').className = 'bi bi-exclamation-triangle';
            notice.querySelector('span').textContent = error.message;
            notice.classList.add('is-visible', 'is-error');
            isSubmitting = false;
            submitButton.disabled = false;
        }
    });
})();
</script>
@endpush