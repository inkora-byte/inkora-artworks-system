@extends('layouts.app')

@section('title', 'Material Adjustments')

@section('content')
    <h1 class="h3 mb-4">Materials — Stock & Adjustments</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="material-adjustments-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Material</th>
                        <th>Current Stock</th>
                        <th>Minimum Stock</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materials as $material)
                        <tr>
                            <td><code>{{ $material->sku }}</code></td>
                            <td>{{ $material->name }}</td>
                            <td>{{ $material->current_stock_cache }} {{ $material->materialUnit->name }}</td>
                            <td>{{ $material->minimum_stock }} {{ $material->materialUnit->name }}</td>
                            <td>
                                @if ($material->needsRestock())
                                    <x-status-badge status="Low Stock" color="danger" />
                                @else
                                    <x-status-badge status="OK" color="success" />
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('materials.adjustments.create', $material) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-sliders me-1"></i> Adjust
                                </a>
                                <a href="{{ route('inventory.movements.index', ['material_id' => $material->id]) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-clock-rotate-left me-1"></i> History
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#material-adjustments-table');
        });
    </script>
@endsection
