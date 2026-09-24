<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input
            type="text" name="name" id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $productType->name ?? '') }}"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="code" class="form-label">
            Code <span class="text-muted">(short, used in SKU generation — e.g. "MB")</span>
        </label>
        <input
            type="text" name="code" id="code"
            class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $productType->code ?? '') }}"
            maxlength="10"
            required
        >
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="sort_order" class="form-label">Sort Order</label>
        <input
            type="number" name="sort_order" id="sort_order"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $productType->sort_order ?? 0) }}"
            min="0"
        >
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input
                type="checkbox" name="is_active" id="is_active" value="1"
                class="form-check-input"
                {{ old('is_active', $productType->is_active ?? true) ? 'checked' : '' }}
            >
            <label for="is_active" class="form-check-label">Active</label>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">
        {{ $productType ? 'Update Product Type' : 'Create Product Type' }}
    </button>
    <a href="{{ route('catalog.product-types.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
