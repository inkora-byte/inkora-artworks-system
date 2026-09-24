@extends('layouts.app')

@section('title', 'Roles')

@section('content')
    <h1 class="h3 mb-1">Roles</h1>
    <p class="text-muted mb-4">
        Roles and their permissions are seeded, not managed here — see
        <code>RoleAndPermissionSeeder</code>. This page is read-only.
    </p>

    <div class="row g-3">
        @foreach ($roles as $role)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5">{{ $role->name }}</h2>
                        <p class="text-muted small mb-2">{{ $role->permissions->count() }} permission(s)</p>
                        <ul class="list-unstyled small mb-0">
                            @foreach ($role->permissions as $permission)
                                <li><i class="fa-solid fa-check text-success me-1"></i> {{ $permission->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
