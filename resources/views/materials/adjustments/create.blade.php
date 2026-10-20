@extends('layouts.app')

@section('title', 'Adjust Material Stock')

@section('content')
    <h1 class="h3 mb-1">Adjust Material Stock</h1>
    <p class="text-muted mb-4">
        {{ $material->name }} <code>{{ $material->sku }}</code>
        — current stock: <strong>{{ $material->current_stock_cache }} {{ $material->materialUnit->name }}</strong>
    </p>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('materials.adjustments.store') }}">
                @csrf
                <input type="hidden" name="material_id" value="{{ $material->id }}">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="quantity_change" class="form-label">
                            Quantity Change <span class="text-muted">(positive to add, negative to remove — supports decimals)</span>
                        </label>
                        <input
                            type="number" step="0.01" name="quantity_change" id="quantity_change"
                            class="form-control @error('quantity_change') is-invalid @enderror"
                            value="{{ old('quantity_change') }}"
                            required
                        >
                        @error('quantity_change')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="adjustment_reason_id" class="form-label">Reason</label>
                        <select name="adjustment_reason_id" id="adjustment_reason_id" class="form-select @error('adjustment_reason_id') is-invalid @enderror" required>
                            <option value="" disabled selected>Select a reason</option>
                            @foreach ($reasons as $reason)
                                <option value="{{ $reason->id }}" {{ old('adjustment_reason_id') == $reason->id ? 'selected' : '' }}>
                                    {{ $reason->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('adjustment_reason_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes <span class="text-muted">(optional)</span></label>
                        <textarea name="notes" id="notes" rows="2" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Record Adjustment</button>
                    <a href="{{ route('materials.adjustments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
