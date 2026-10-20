@extends('layouts.app')

@section('title', 'Materials')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Materials</h1>
        <a href="{{ route('materials.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> New Material
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="materials-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Unit Cost</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materials as $material)
                        <tr>
                            <td><code>{{ $material->sku }}</code></td>
                            <td>{{ $material->name }}{{ $material->brand ? " ({$material->brand})" : '' }}</td>
                            <td>{{ $material->materialCategory->name }}</td>
                            <td>{{ $material->materialUnit->name }}</td>
                            <td>
                                {{ $material->unit_cost_cache !== null ? '₱'.number_format((float) $material->unit_cost_cache, 4) : '—' }}
                            </td>
                            <td>{{ $material->current_stock_cache }} {{ $material->materialUnit->name }}</td>
                            <td>
                                <x-status-badge :status="$material->status->label()" :color="$material->status->color()" />
                            </td>
                            <td class="text-end">
                                <a href="{{ route('materials.edit', $material) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <x-confirm-button
                                    :action="route('materials.toggle-status', $material)"
                                    method="POST"
                                    :label="$material->status->value === 'active' ? 'Phase Out' : 'Reactivate'"
                                    :title="$material->status->value === 'active' ? 'Phase out this material?' : 'Reactivate this material?'"
                                    text="Purchase and usage history are never affected — this only hides it from new recipes."
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#materials-table');
        });
    </script>
@endsection
