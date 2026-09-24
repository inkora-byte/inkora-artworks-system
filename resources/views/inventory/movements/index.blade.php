@extends('layouts.app')

@section('title', 'Movement History')

@section('content')
    <h1 class="h3 mb-4">Movement History</h1>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.movements.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="product_variant_id" class="form-label">Filter by Item</label>
                    <select name="product_variant_id" id="product_variant_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All items</option>
                        @foreach ($variants as $variant)
                            <option value="{{ $variant->id }}" {{ request('product_variant_id') == $variant->id ? 'selected' : '' }}>
                                {{ $variant->sku }} — {{ $variant->product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if (request('product_variant_id'))
                    <div class="col-md-2">
                        <a href="{{ route('inventory.movements.index') }}" class="btn btn-outline-secondary">Clear Filter</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Change</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>Reason</th>
                        <th>Staff</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('M j, Y g:i A') }}</td>
                            <td>
                                @if ($movement->stockable)
                                    <code>{{ $movement->stockable->sku }}</code>
                                @else
                                    <span class="text-muted">(deleted item)</span>
                                @endif
                            </td>
                            <td>
                                <x-status-badge :status="$movement->type->label()" color="secondary" />
                            </td>
                            <td class="{{ $movement->quantity_change >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $movement->quantity_change >= 0 ? '+' : '' }}{{ (int) $movement->quantity_change }}
                            </td>
                            <td>{{ (int) $movement->quantity_before }}</td>
                            <td>{{ (int) $movement->quantity_after }}</td>
                            <td>{{ $movement->adjustmentReason->name ?? '—' }}</td>
                            <td>{{ $movement->creator->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No stock movements recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $movements->links() }}
        </div>
    </div>
@endsection
