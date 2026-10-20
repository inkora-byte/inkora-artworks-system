@extends('layouts.app')

@section('title', 'New Material')

@section('content')
    <h1 class="h3 mb-4">New Material</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('materials.store') }}">
                @csrf
                @include('materials._form', ['material' => null, 'categories' => $categories, 'units' => $units])
            </form>
        </div>
    </div>
@endsection
