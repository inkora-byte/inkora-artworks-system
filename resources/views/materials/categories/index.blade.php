@extends('layouts.app')

@section('title', 'Material Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Material Categories</h1>
        <p class="text-muted mb-0">Manage the categories used to group and code your raw materials.</p>
    </div>
    <div>
        <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-ruler"></i> Manage Units
        </a>
        @can('create', \App\Models\MaterialCategory::class)
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> New Category
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
                        <th>Code</th>
                        <th>Name</th>
                        <th class="text-center">Materials</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $category->code }}</span></td>
                            <td>{{ $category->name }}</td>
                            <td class="text-center">{{ $category->materials_count }}</td>
                            <td class="text-center">
                                <x-status-badge :active="$category->is_active" />
                            </td>
                            <td class="text-end">
                                @can('update', $category)
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No material categories yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $categories->links() }}
</div>
@endsection
