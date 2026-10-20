@extends('layouts.app')

@section('title', 'Edit Material')

@section('content')
    <h1 class="h3 mb-4">Edit Material</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('materials.update', $material) }}">
                @csrf
                @method('PUT')
                @include('materials._form', ['material' => $material, 'categories' => $categories, 'units' => $units])
            </form>
        </div>
    </div>
@endsection
