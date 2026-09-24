<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Product Name</label>
        <input
            type="text" name="name" id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $product->name ?? '') }}"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="product_type_id" class="form-label">Product Type</label>
        <select name="product_type_id" id="product_type_id" class="form-select @error('product_type_id') is-invalid @enderror" required>
            <option value="" disabled {{ old('product_type_id', $product->product_type_id ?? '') ? '' : 'selected' }}>
                Select a type
            </option>
            @foreach ($productTypes as $type)
                <option
                    value="{{ $type->id }}"
                    {{ (int) old('product_type_id', $product->product_type_id ?? 0) === $type->id ? 'selected' : '' }}
                >
                    {{ $type->name }} ({{ $type->code }})
                </option>
            @endforeach
        </select>
        @error('product_type_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="design_id" class="form-label">Design <span class="text-muted">(optional)</span></label>
        <select name="design_id" id="design_id" class="form-select @error('design_id') is-invalid @enderror">
            <option value="">— No design —</option>
            @foreach ($designs as $design)
                <option
                    value="{{ $design->id }}"
                    {{ (int) old('design_id', $product->design_id ?? 0) === $design->id ? 'selected' : '' }}
                >
                    {{ $design->name }}
                </option>
            @endforeach
        </select>
        @error('design_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Description <span class="text-muted">(optional)</span></label>
        <textarea
            name="description" id="description" rows="2"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12"><hr></div>

    <div class="col-12">
        <h2 class="h6 text-muted text-uppercase">
            {{ $product ? 'Default Variant' : 'Pricing & Fulfillment' }}
        </h2>
        @if ($product)
            <p class="small text-muted">
                SKU: <code>{{ $product->defaultVariant->sku ?? '—' }}</code>
                — auto-generated, cannot be changed here.
            </p>
        @else
            <p class="small text-muted">
                A default variant (SKU auto-generated) is created automatically with the details below.
            </p>
        @endif
    </div>

    <div class="col-md-4">
        <label for="price" class="form-label">Price (₱)</label>
        <input
            type="number" step="0.01" min="0" name="price" id="price"
            class="form-control @error('price') is-invalid @enderror"
            value="{{ old('price', $product->defaultVariant->price ?? '') }}"
            required
        >
        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="fulfillment_method" class="form-label">Fulfillment Method</label>
        @php
            $currentFulfillment = old('fulfillment_method', $product->defaultVariant->fulfillment_method->value ?? 'in_house');
        @endphp
        <select name="fulfillment_method" id="fulfillment_method" class="form-select @error('fulfillment_method') is-invalid @enderror" required>
            <option value="in_house" {{ $currentFulfillment === 'in_house' ? 'selected' : '' }}>In-House</option>
            <option value="outsourced" {{ $currentFulfillment === 'outsourced' ? 'selected' : '' }}>Outsourced</option>
        </select>
        @error('fulfillment_method')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="minimum_stock" class="form-label">Minimum Stock</label>
        <input
            type="number" min="0" name="minimum_stock" id="minimum_stock"
            class="form-control @error('minimum_stock') is-invalid @enderror"
            value="{{ old('minimum_stock', $product->defaultVariant->minimum_stock ?? 0) }}"
        >
        @error('minimum_stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="time_to_make_minutes" class="form-label">
            Time to Make (minutes) <span class="text-muted">(optional)</span>
        </label>
        <input
            type="number" min="0" name="time_to_make_minutes" id="time_to_make_minutes"
            class="form-control @error('time_to_make_minutes') is-invalid @enderror"
            value="{{ old('time_to_make_minutes', $product->defaultVariant->time_to_make_minutes ?? '') }}"
        >
        @error('time_to_make_minutes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12"><hr></div>

    <div class="col-12">
        <label for="photos" class="form-label">
            Product Photos <span class="text-muted">(PNG, JPG, WEBP — max 10MB each)</span>
        </label>
        <input
            type="file" name="photos[]" id="photos" multiple accept="image/*"
            class="form-control @error('photos') is-invalid @enderror"
        >
        @error('photos')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @error('photos.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        @if ($product && $product->getMedia('gallery')->isNotEmpty())
            <div class="mt-3">
                <p class="small text-muted mb-2">Already uploaded:</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($product->getMedia('gallery') as $media)
                        <img
                            src="{{ $media->getUrl() }}"
                            alt="{{ $product->name }}"
                            class="img-thumbnail"
                            style="width: 90px; height: 90px; object-fit: cover;"
                        >
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">
        {{ $product ? 'Update Product' : 'Create Product' }}
    </button>
    <a href="{{ route('catalog.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
