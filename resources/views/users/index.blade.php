@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Users</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> New User
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="users-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                            <td>
                                <x-status-badge
                                    :status="$user->is_active ? 'Active' : 'Deactivated'"
                                    :color="$user->is_active ? 'success' : 'secondary'"
                                />
                            </td>
                            <td class="text-end">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if (auth()->user()->isNot($user))
                                    <x-confirm-button
                                        :action="route('users.toggle-active', $user)"
                                        method="POST"
                                        :label="$user->is_active ? 'Deactivate' : 'Activate'"
                                        :title="$user->is_active ? 'Deactivate this user?' : 'Reactivate this user?'"
                                        :text="$user->is_active
                                            ? 'They will be immediately logged out and unable to sign in until reactivated.'
                                            : 'They will be able to log in again.'"
                                    />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#users-table');
        });
    </script>
@endsection
