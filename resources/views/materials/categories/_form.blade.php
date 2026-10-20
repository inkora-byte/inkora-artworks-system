@php
    /** @var \App\Models\MaterialCategory|null $category */
    $category = $category ?? null;
@endphp

<div class="mb-3">
    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
    <input
        type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $category->name ?? '') }}"
        required
        autofocus
    >
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
    <input
        type="text"
        name="code"
        id="code"
        maxlength="10"
        class="form-control text-uppercase @error('code') is-invalid @enderror"
        value="{{ old('code', $category->code ?? '') }}"
        required
    >
    <div class="form-text">Short code used as the prefix for this category's material SKUs (e.g. SPR, PKG, INK). Max 10 characters.</div>
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check form-switch">
    <input
        type="checkbox"
        name="is_active"
        id="is_active"
        value="1"
        class="form-check-input"
        {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
    >
    <label for="is_active" class="form-check-label">Active</label>
    <div class="form-text">Inactive categories are hidden from the "New Material" form but existing materials keep their category.</div>
</div>
