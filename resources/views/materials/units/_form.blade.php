@php
    /** @var \App\Models\MaterialUnit|null $unit */
    $unit = $unit ?? null;
@endphp

<div class="mb-3">
    <label for="name" class="form-label">Unit Name <span class="text-danger">*</span></label>
    <input
        type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $unit->name ?? '') }}"
        required
        autofocus
        placeholder="e.g. pcs, sheets, rolls, meters"
    >
    <div class="form-text">The unit of measure used when tracking stock for this material (e.g. "pcs", "sheets", "rolls").</div>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
