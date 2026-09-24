@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <h1 class="h3 mb-4">Edit User</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                @include('users._form', ['user' => $user])
            </form>
        </div>
    </div>
@endsection
