@extends('layouts.app')

@section('title', 'New Material Category')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0">New Material Category</h1>
    <p class="text-muted mb-0">Add a new category to group your raw materials by.</p>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            @include('materials.categories._form')

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Create Category
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
