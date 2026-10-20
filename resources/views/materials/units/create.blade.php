@extends('layouts.app')

@section('title', 'New Material Unit')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0">New Material Unit</h1>
    <p class="text-muted mb-0">Add a new unit of measure for tracking material stock.</p>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('units.store') }}">
            @csrf
            @include('materials.units._form')

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Create Unit
                </button>
                <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
