<span data-users-summary hidden><strong>{{ $users->total() }}</strong> {{ $users->total() === 1 ? 'user' : 'users' }}{{ !empty($search) ? ' matching your search' : ' in the platform' }}</span>
@if($users->count() > 0)
    <div class="table-responsive"><table class="table users-table"><thead><tr><th>User</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead><tbody>
        @foreach($users as $user)
            <tr>
                <td><span class="user-name">{{ $user->name }}</span><span class="user-email">{{ $user->email }}</span></td>
                <td><span class="user-badge user-role">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span></td>
                <td><span class="user-badge user-status-{{ $user->status === 'active' ? 'active' : 'suspended' }}">{{ ucfirst($user->status) }}</span></td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td><div class="user-controls">
                    <form class="role-form" method="POST" action="{{ route('admin.users.role', $user->id) }}">@csrf @method('PUT')<select name="role" aria-label="Role for {{ $user->name }}">@foreach(['job_seeker' => 'Job seeker', 'employer' => 'Employer', 'moderator' => 'Moderator', 'admin' => 'Admin'] as $role => $label)<option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select><button class="btn" type="submit">Save role</button></form>
                    <form class="status-form" method="POST" action="{{ route('admin.users.status', $user->id) }}">@csrf @method('PUT')<button class="btn" type="submit">{{ $user->status === 'active' ? 'Suspend' : 'Activate' }}</button></form>
                    <form class="delete-form" method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?');">@csrf @method('DELETE')<button class="btn" type="submit">Delete</button></form>
                </div></td>
            </tr>
        @endforeach
    </tbody></table></div>
    <div class="users-pagination">
        <form class="per-page-form" method="GET" action="{{ route('admin.users') }}">
            @if(!empty($search))<input type="hidden" name="search" value="{{ $search }}">@endif
            <label for="users_per_page">Show</label>
            <select id="users_per_page" name="per_page" aria-label="Users per page">
                @foreach([5, 10, 20, 50] as $option)<option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>@endforeach
            </select>
            <span>per page</span>
        </form>
        @if($users->hasPages())
            <span class="pagination-info">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}</span>
            <nav class="pagination-nav" aria-label="Users pagination">
                <a class="pagination-link {{ $users->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $users->previousPageUrl() ?? '#' }}">Previous</a>
                @for($page = 1; $page <= $users->lastPage(); $page++)<a class="pagination-link {{ $users->currentPage() === $page ? 'is-current' : '' }}" href="{{ $users->url($page) }}">{{ $page }}</a>@endfor
                <a class="pagination-link {{ $users->currentPage() === $users->lastPage() ? 'is-disabled' : '' }}" href="{{ $users->nextPageUrl() ?? '#' }}">Next</a>
            </nav>
        @endif
    </div>
@else
    <div class="users-empty"><i class="bi bi-search"></i><h2>No users found</h2><p>Try another name, email, role, or status.</p></div>
@endif
