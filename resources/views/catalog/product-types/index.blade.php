@extends('layouts.app')

@section('title', 'Product Types')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Product Types</h1>
        <a href="{{ route('catalog.product-types.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> New Product Type
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="product-types-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productTypes as $productType)
                        <tr>
                            <td>{{ $productType->name }}</td>
                            <td><code>{{ $productType->code }}</code></td>
                            <td>{{ $productType->sort_order }}</td>
                            <td>
                                <x-status-badge
                                    :status="$productType->is_active ? 'Active' : 'Inactive'"
                                    :color="$productType->is_active ? 'success' : 'secondary'"
                                />
                            </td>
                            <td class="text-end">
                                <a href="{{ route('catalog.product-types.edit', $productType) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
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
            new DataTable('#product-types-table');
        });
    </script>
@endsection
