@extends('layouts.app')
@section('title', 'Manage Categories')
@section('content')
<div class="my-4"><h1 class="fw-bold mb-4">Manage Categories</h1>
    <div class="row"><div class="col-md-4"><div class="card"><div class="card-header">Add Category</div>
                    <div class="card-body"><form method="POST" action="{{ route('admin.categories.store') }}">@csrf
                            <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Category Name" required></div>
                            <button type="submit" class="btn btn-primary w-100">Add Category</button></form></div></div></div>
        <div class="col-md-8"><div class="card"><div class="card-body"><table class="table">
                        <thead><tr><th>Name</th><th>Jobs Count</th><th>Actions</th></tr></thead>
                        <tbody>@foreach($categories ?? [] as $cat)
                            <tr><td>{{ $cat->name }}</td><td>{{ $cat->jobs_count ?? 0 }}</td>
                                <td><form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" class="d-inline">@csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@endforeach
                        </tbody></table></div></div></div></div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!form.matches('form[action*="categories"]') || form.dataset.submitting === 'true') return;
    form.dataset.submitting = 'true';
    form.querySelector('button[type="submit"]')?.setAttribute('disabled', 'disabled');
});
</script>
@endpush