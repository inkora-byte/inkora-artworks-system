@extends('layouts.app')

@section('title', 'New User')

@section('content')
    <h1 class="h3 mb-4">New User</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                @include('users._form', ['user' => null])
            </form>
        </div>
    </div>
@endsection
