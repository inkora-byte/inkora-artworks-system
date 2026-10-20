<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Material Name</label>
        <input
            type="text" name="name" id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $material->name ?? '') }}"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="brand" class="form-label">Brand <span class="text-muted">(optional)</span></label>
        <input
            type="text" name="brand" id="brand"
            class="form-control @error('brand') is-invalid @enderror"
            value="{{ old('brand', $material->brand ?? '') }}"
        >
        @error('brand')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="material_category_id" class="form-label">Category</label>
        <select name="material_category_id" id="material_category_id" class="form-select @error('material_category_id') is-invalid @enderror" required>
            <option value="" disabled {{ old('material_category_id', $material->material_category_id ?? '') ? '' : 'selected' }}>
                Select a category
            </option>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ (int) old('material_category_id', $material->material_category_id ?? 0) === $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }} ({{ $category->code }})
                </option>
            @endforeach
        </select>
        @error('material_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($material)
            <p class="small text-muted mt-1">SKU: <code>{{ $material->sku }}</code> — auto-generated, cannot be changed.</p>
        @endif
    </div>

    <div class="col-md-6">
        <label for="material_unit_id" class="form-label">Unit</label>
        <select name="material_unit_id" id="material_unit_id" class="form-select @error('material_unit_id') is-invalid @enderror" required>
            <option value="" disabled {{ old('material_unit_id', $material->material_unit_id ?? '') ? '' : 'selected' }}>
                Select a unit
            </option>
            @foreach ($units as $unit)
                <option
                    value="{{ $unit->id }}"
                    {{ (int) old('material_unit_id', $material->material_unit_id ?? 0) === $unit->id ? 'selected' : '' }}
                >
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>
        @error('material_unit_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="minimum_stock" class="form-label">Minimum Stock</label>
        <input
            type="number" step="0.01" min="0" name="minimum_stock" id="minimum_stock"
            class="form-control @error('minimum_stock') is-invalid @enderror"
            value="{{ old('minimum_stock', $material->minimum_stock ?? 0) }}"
        >
        @error('minimum_stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="notes" class="form-label">Notes <span class="text-muted">(optional)</span></label>
        <textarea
            name="notes" id="notes" rows="2"
            class="form-control @error('notes') is-invalid @enderror"
        >{{ old('notes', $material->notes ?? '') }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">
        {{ $material ? 'Update Material' : 'Create Material' }}
    </button>
    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
