<nav class="navbar navbar-expand topbar px-4">
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-muted small">
            <i class="fa-regular fa-user me-1"></i>{{ auth()->user()->name }}
        </span>
        <form method="POST" action="{{ route('logout') }}" class="mb-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Log out
            </button>
        </form>
    </div>
</nav>
