@extends('layouts.app')

@section('title', 'Edit Job')

@push('styles')
<style>
    .job-create { --job-ink: #18232d; --job-muted: #6d7a80; --job-line: #dce5e4; --job-mint: #ccefe3; --job-green: #087f67; --job-coral: #f27864; padding: 42px 0 64px; color: var(--job-ink); }
    .job-create-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 28px; }
    .job-kicker { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: var(--job-green); font-size: .72rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .job-kicker::before { content: ''; width: 22px; height: 2px; background: var(--job-coral); }
    .job-create-header h1 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -.05em; }
    .job-create-header p { margin: 8px 0 0; color: var(--job-muted); }
    .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--job-muted); font-size: .82rem; font-weight: 700; text-decoration: none; }
    .back-link:hover { color: var(--job-green); }
    .job-form { border: 1px solid var(--job-line); border-radius: 12px; background: #fff; overflow: hidden; }
    .form-section { padding: 28px clamp(20px, 4vw, 38px); border-bottom: 1px solid var(--job-line); }
    .form-section:last-of-type { border-bottom: 0; }
    .section-title { display: flex; align-items: flex-start; gap: 13px; margin-bottom: 22px; }
    .section-icon { display: grid; place-items: center; flex: 0 0 34px; height: 34px; border-radius: 9px; color: var(--job-green); background: var(--job-mint); }
    .section-title h2 { margin: 0; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: 1.15rem; letter-spacing: -.03em; }
    .section-title p { margin: 3px 0 0; color: var(--job-muted); font-size: .8rem; }
    .job-label { display: block; margin: 0 0 8px; color: var(--job-ink); font-size: .8rem; font-weight: 700; }
    .job-input, .job-select, .job-textarea { width: 100%; border: 1px solid var(--job-line); border-radius: 9px; padding: 13px 14px; outline: 0; color: var(--job-ink); background: #fff; font: inherit; font-size: .88rem; transition: border-color .2s, box-shadow .2s; }
    .job-input:focus, .job-select:focus, .job-textarea:focus { border-color: var(--job-green); box-shadow: 0 0 0 4px rgba(8,127,103,.1); }
    .job-input.is-invalid, .job-select.is-invalid, .job-textarea.is-invalid { border-color: var(--job-coral); }
    .job-textarea { min-height: 150px; resize: vertical; }
    .requirements-area { min-height: 150px; }
    .field-help { display: block; margin-top: 7px; color: var(--job-muted); font-size: .74rem; }
    .field-error { margin: 7px 0 0; color: #c64c3d; font-size: .76rem; }
    .field-group { margin-bottom: 20px; }
    .field-group:last-child { margin-bottom: 0; }
    .form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding: 20px clamp(20px, 4vw, 38px); background: #f7faf8; }
    .cancel-link { padding: 12px 16px; color: var(--job-muted); font-size: .84rem; font-weight: 700; text-decoration: none; }
    .cancel-link:hover { color: var(--job-green); }
    .submit-job { display: inline-flex; align-items: center; gap: 9px; border: 0; border-radius: 9px; padding: 13px 18px; color: #fff; background: var(--job-green); cursor: pointer; font: 700 .86rem 'DM Sans', sans-serif; transition: background .2s; }
    .submit-job:hover { color: #fff; background: #05634f; }
    .submit-job i { font-size: .75rem; }
    @media (max-width: 620px) { .job-create { padding: 28px 0 44px; } .job-create-header { align-items: flex-start; flex-direction: column; gap: 14px; } .back-link { order: -1; } .form-section { padding: 22px 18px; } .form-actions { align-items: stretch; flex-direction: column-reverse; padding: 17px 18px; } .submit-job { justify-content: center; width: 100%; } .cancel-link { text-align: center; } }
</style>
@endpush

@section('content')
<div class="container job-create">
    <div class="job-create-header">
        <div>
            <span class="job-kicker">Employer workspace</span>
            <h1>Edit job</h1>
            <p>Update the details for this role and keep your hiring page current.</p>
        </div>
        <a class="back-link" href="{{ route('employer.jobs') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to jobs</a>
    </div>

    <form class="job-form" method="POST" action="{{ route('employer.jobs.update', $job->id) }}">
        @csrf
        @method('PUT')

        <section class="form-section">
            <div class="section-title"><span class="section-icon"><i class="bi bi-briefcase" aria-hidden="true"></i></span><div><h2>Job basics</h2><p>Start with the essentials candidates need to know.</p></div></div>
            <div class="row g-3">
                <div class="col-md-6 field-group mb-0">
                    <label class="job-label" for="title">Job title</label>
                    <input class="job-input @error('title') is-invalid @enderror" id="title" name="title" type="text" value="{{ old('title', $job->title) }}" placeholder="e.g. Senior Product Designer" required>
                    @error('title')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="col-md-6 field-group mb-0">
                    <label class="job-label" for="location">Location</label>
                    <input class="job-input @error('location') is-invalid @enderror" id="location" name="location" type="text" value="{{ old('location', $job->location) }}" placeholder="e.g. Lahore or Remote" required>
                    @error('location')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6 field-group mb-0">
                    <label class="job-label" for="category_id">Category</label>
                    <select class="job-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $job->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="col-md-6 field-group mb-0">
                    <label class="job-label" for="job_type">Job type</label>
                    <select class="job-select @error('job_type') is-invalid @enderror" id="job_type" name="job_type" required>
                        <option value="full-time" {{ old('job_type', $job->job_type) == 'full-time' ? 'selected' : '' }}>Full-time</option>
                        <option value="part-time" {{ old('job_type', $job->job_type) == 'part-time' ? 'selected' : '' }}>Part-time</option>
                        <option value="contract" {{ old('job_type', $job->job_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="remote" {{ old('job_type', $job->job_type) == 'remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                    @error('job_type')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="form-section">
            <div class="section-title"><span class="section-icon"><i class="bi bi-file-text" aria-hidden="true"></i></span><div><h2>Role details</h2><p>Give candidates a clear picture of the work.</p></div></div>
            <div class="row g-3">
                <div class="col-md-6 field-group mb-0">
                    <label class="job-label" for="description">Description</label>
                    <textarea class="job-textarea @error('description') is-invalid @enderror" id="description" name="description" placeholder="Tell candidates what they will work on and why this role matters." required>{{ old('description', $job->description) }}</textarea>
                    @error('description')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="col-md-6 field-group mb-0">
                    <label class="job-label" for="requirements">Requirements <span class="fw-normal text-muted">(optional)</span></label>
                    <textarea class="job-textarea requirements-area @error('requirements') is-invalid @enderror" id="requirements" name="requirements" placeholder="List the skills, experience, or qualifications that will help someone succeed.">{{ old('requirements', $job->requirements) }}</textarea>
                    @error('requirements')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="form-section">
            <div class="section-title"><span class="section-icon"><i class="bi bi-calendar-check" aria-hidden="true"></i></span><div><h2>Compensation & timing</h2><p>Optional details that help the right people decide.</p></div></div>
            <div class="row g-3">
                <div class="col-md-6 field-group mb-0">
                    <label class="job-label" for="salary_min">Minimum salary <span class="fw-normal text-muted">(optional)</span></label>
                    <input class="job-input @error('salary_min') is-invalid @enderror" id="salary_min" name="salary_min" type="number" min="0" step="0.01" value="{{ old('salary_min', $job->salary_min) }}" placeholder="e.g. 80000">
                    @error('salary_min')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="col-md-6 field-group mb-0">
                    <label class="job-label" for="salary_max">Maximum salary <span class="fw-normal text-muted">(optional)</span></label>
                    <input class="job-input @error('salary_max') is-invalid @enderror" id="salary_max" name="salary_max" type="number" min="0" step="0.01" value="{{ old('salary_max', $job->salary_max) }}" placeholder="e.g. 120000">
                    @error('salary_max')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="field-group mt-3 mb-0">
                <label class="job-label" for="deadline">Application deadline <span class="fw-normal text-muted">(optional)</span></label>
                <input class="job-input @error('deadline') is-invalid @enderror" id="deadline" name="deadline" type="date" value="{{ old('deadline', optional($job->deadline)->format('Y-m-d')) }}">
                @error('deadline')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </section>

        <div class="form-actions">
            <a class="cancel-link" href="{{ route('employer.jobs') }}">Cancel</a>
            <button class="submit-job" type="submit"><i class="bi bi-check-circle" aria-hidden="true"></i> Save changes</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form.job-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                const submitButton = form.querySelector('button[type="submit"]');
                if (!submitButton) return;

                if (form.dataset.submitted === 'true') {
                    event.preventDefault();
                    event.stopPropagation();
                    return;
                }

                form.dataset.submitted = 'true';
                submitButton.disabled = true;
                submitButton.setAttribute('aria-disabled', 'true');
                submitButton.style.opacity = '0.7';
                submitButton.style.cursor = 'not-allowed';
            });
        });
    });
</script>
@endpush
