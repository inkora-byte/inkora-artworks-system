@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Products</h1>
        <a href="{{ route('catalog.products.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> New Product
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="products-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Design</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Fulfillment</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @php $variant = $product->variants->firstWhere('is_default_variant', true); @endphp
                        <tr>
                            <td>
                                @if ($product->getFirstMediaUrl('gallery'))
                                    <img
                                        src="{{ $product->getFirstMediaUrl('gallery') }}"
                                        alt="{{ $product->name }}"
                                        class="img-thumbnail"
                                        style="width: 50px; height: 50px; object-fit: cover;"
                                    >
                                @else
                                    <span class="text-muted small">No photo</span>
                                @endif
                            </td>
                            <td><code>{{ $variant->sku ?? '—' }}</code></td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->productType->name }}</td>
                            <td>{{ $product->design->name ?? '—' }}</td>
                            <td>₱{{ number_format((float) ($variant->price ?? 0), 2) }}</td>
                            <td>{{ $variant->current_stock_cache ?? 0 }}</td>
                            <td>
                                @if ($variant)
                                    <x-status-badge
                                        :status="$variant->fulfillment_method->label()"
                                        :color="$variant->fulfillment_method->value === 'outsourced' ? 'info' : 'primary'"
                                    />
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <x-status-badge :status="$product->status->label()" :color="$product->status->color()" />
                            </td>
                            <td class="text-end">
                                <a href="{{ route('catalog.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <x-confirm-button
                                    :action="route('catalog.products.toggle-status', $product)"
                                    method="POST"
                                    :label="$product->status->value === 'active' ? 'Discontinue' : 'Reactivate'"
                                    :title="$product->status->value === 'active' ? 'Discontinue this product?' : 'Reactivate this product?'"
                                    text="Sales history and stock records are never affected — this only hides it from new order entry."
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
            new DataTable('#products-table');
        });
    </script>
@endsection
