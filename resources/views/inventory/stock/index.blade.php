@extends('layouts.app')

@section('title', 'Stock Levels')

@section('content')
    <h1 class="h3 mb-4">Stock Levels</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="stock-levels-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Product</th>
                        <th>Current Stock</th>
                        <th>Minimum Stock</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($variants as $variant)
                        <tr>
                            <td><code>{{ $variant->sku }}</code></td>
                            <td>{{ $variant->product->name }}</td>
                            <td>{{ $variant->current_stock_cache }}</td>
                            <td>{{ $variant->minimum_stock }}</td>
                            <td>
                                @if ($variant->needsRestock())
                                    <x-status-badge status="Low Stock" color="danger" />
                                @else
                                    <x-status-badge status="OK" color="success" />
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('inventory.adjustments.create', $variant) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-sliders me-1"></i> Adjust
                                </a>
                                <a href="{{ route('inventory.movements.index', ['product_variant_id' => $variant->id]) }}" class="btn btn-sm btn-outline-secondary">
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
            new DataTable('#stock-levels-table');
        });
    </script>
@endsection
