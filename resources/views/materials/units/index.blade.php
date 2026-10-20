@extends('layouts.app')

@section('title', 'Material Units')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Material Units</h1>
        <p class="text-muted mb-0">Manage the units of measure used to track material stock.</p>
    </div>
    <div>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-layer-group"></i> Manage Categories
        </a>
        @can('create', \App\Models\MaterialUnit::class)
            <a href="{{ route('units.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> New Unit
            </a>
        @endcan
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Materials</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $unit)
                        <tr>
                            <td>{{ $unit->name }}</td>
                            <td class="text-center">{{ $unit->materials_count }}</td>
                            <td class="text-end">
                                @can('update', $unit)
                                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No material units yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $units->links() }}
</div>
@endsection
