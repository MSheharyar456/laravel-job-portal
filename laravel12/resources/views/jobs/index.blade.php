@extends('layouts.app')

@section('title', 'Browse Jobs - Job Portal')

@push('styles')
<style>
    .browse-jobs-page .card { transition: none; }
    .browse-jobs-page .card:hover { box-shadow: var(--shadow-sm); transform: none; }
    #public-jobs-results.is-loading { opacity: .5; pointer-events: none; transition: opacity .2s; }
    .browse-jobs-page { --browse-ink: #18232d; --browse-muted: #6d7a80; --browse-line: #dce5e4; --browse-teal: #087f67; --browse-mint: #ccefe3; max-width: 1120px; margin: 0 auto; padding: 18px 0 54px; color: var(--browse-ink); }
    .browse-jobs-page > h1 { margin-bottom: 22px !important; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(2rem, 4vw, 3.1rem); letter-spacing: -.05em; }
    .browse-jobs-page > .card.mb-4 { border: 1px solid var(--browse-line); border-radius: 12px; box-shadow: none; }
    .browse-jobs-page > .card.mb-4 .card-body { padding: 20px; }
    .browse-jobs-page .form-control, .browse-jobs-page .form-select { min-height: 46px; border: 1px solid var(--browse-line); border-radius: 8px; color: var(--browse-ink); font-size: .86rem; }
    .browse-jobs-page .form-control:focus, .browse-jobs-page .form-select:focus { border-color: var(--browse-teal); box-shadow: 0 0 0 3px rgba(8,127,103,.1); }
    .browse-jobs-page .btn-primary { min-height: 46px; border: 0; border-radius: 8px; background: var(--browse-teal); font-size: .82rem; font-weight: 700; }
    .browse-jobs-page .btn-primary:hover { background: #05634f; }
    .browse-jobs-page #public-jobs-results > p { color: var(--browse-muted) !important; font-size: .82rem; }
    .browse-jobs-page #public-jobs-results > p::first-letter { color: var(--browse-ink); }
    .browse-jobs-page #public-jobs-results .row { margin-top: 0; }
    .browse-jobs-page #public-jobs-results .row > [class*="col-"] { display: flex; }
    .browse-jobs-page #public-jobs-results .card { width: 100%; border: 1px solid var(--browse-line); border-radius: 12px; box-shadow: none; }
    .browse-jobs-page #public-jobs-results .card-body { display: flex; flex-direction: column; padding: 23px; }
    .browse-jobs-page #public-jobs-results h5 { color: var(--browse-ink); font-family: 'Space Grotesk', 'DM Sans', sans-serif; letter-spacing: -.02em; }
    .browse-jobs-page #public-jobs-results .text-muted { color: var(--browse-muted) !important; font-size: .82rem; }
    .browse-jobs-page #public-jobs-results .badge-purple { border-radius: 999px; padding: 7px 10px; color: var(--browse-teal); background: var(--browse-mint); font-size: .7rem; white-space: nowrap; }
    .browse-jobs-page #public-jobs-results .btn-outline-primary { border-color: var(--browse-teal); color: var(--browse-teal); }
    .browse-jobs-page #public-jobs-results .btn-outline-primary:hover { border-color: var(--browse-teal); color: #fff; background: var(--browse-teal); transform: none; }
    .browse-jobs-page #public-jobs-results .btn-outline-primary i { display: inline-block; transition: transform .2s ease; }
    .browse-jobs-page #public-jobs-results .btn-outline-primary:hover i { transform: translateX(4px); }
    .browse-jobs-page .pagination { gap: 5px; }
    .browse-jobs-page .pagination .page-link { border: 1px solid var(--browse-line); border-radius: 7px; color: var(--browse-teal); font-size: .8rem; }
    .browse-jobs-page .pagination .page-item.active .page-link { border-color: var(--browse-teal); color: #fff; background: var(--browse-teal); }
    .browse-jobs-page .pagination .page-item.disabled .page-link { color: #aebbbb; background: #f7faf8; }
    @media (max-width: 700px) { .browse-jobs-page { padding: 8px 0 42px; } .browse-jobs-page > .card.mb-4 .card-body { padding: 15px; } .browse-jobs-page #public-jobs-results .card-body { padding: 19px; } }
</style>
@endpush

@section('content')
<div class="my-4 browse-jobs-page">
    <h1 class="fw-bold mb-4">Browse Jobs</h1>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="jobs-search-form" action="{{ route('jobs.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Job title or keyword..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="location" class="form-control" placeholder="Location" 
                           value="{{ request('location') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Jobs List -->
    <div id="public-jobs-results">@include('jobs._results')</div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('jobs-search-form');
    const results = document.getElementById('public-jobs-results');
    const loadResults = async (url, pushState = true) => {
        results.classList.add('is-loading');
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            if (!response.ok) throw new Error('Unable to load jobs');
            results.innerHTML = await response.text();
            if (pushState) history.pushState({}, '', url);
        } catch (error) { window.location.href = url; }
        finally { results.classList.remove('is-loading'); }
    };
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = new URL(form.action, window.location.origin);
        new FormData(form).forEach((value, key) => value && url.searchParams.set(key, value));
        loadResults(url.toString());
    });
    results.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination a');
        if (!link) return;
        event.preventDefault();
        loadResults(link.href);
    });
    window.addEventListener('popstate', () => loadResults(window.location.href, false));
})();
</script>
@endpush
