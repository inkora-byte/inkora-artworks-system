@php
    $statusValue = old('status', isset($design) && $design ? $design->status->value : 'active');
    $previewableMimes = ['image/png', 'image/jpeg', 'image/svg+xml'];
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Name</label>
        <input
            type="text" name="name" id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $design->name ?? '') }}"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="artist_name" class="form-label">Artist <span class="text-muted">(optional)</span></label>
        <input
            type="text" name="artist_name" id="artist_name"
            class="form-control @error('artist_name') is-invalid @enderror"
            value="{{ old('artist_name', $design->artist_name ?? '') }}"
        >
        @error('artist_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Description <span class="text-muted">(optional)</span></label>
        <textarea
            name="description" id="description" rows="3"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old('description', $design->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ $statusValue === 'active' ? 'selected' : '' }}>Active</option>
            <option value="retired" {{ $statusValue === 'retired' ? 'selected' : '' }}>Retired</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="source_files" class="form-label">
            Source Files <span class="text-muted">(AI, PSD, PNG, JPG, PDF, SVG — max 50MB each)</span>
        </label>
        <input
            type="file" name="source_files[]" id="source_files" multiple
            class="form-control @error('source_files') is-invalid @enderror"
        >
        @error('source_files')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @error('source_files.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        @if (isset($design) && $design && $design->getMedia('source_files')->isNotEmpty())
            <div class="mt-3">
                <p class="small text-muted mb-2">Already uploaded — click any file to open it:</p>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($design->getMedia('source_files') as $media)
                        <div class="text-center" style="width: 110px;">
                            @if (in_array($media->mime_type, $previewableMimes))
                                <a href="{{ route('media.show', $media) }}" target="_blank">
                                    <img
                                        src="{{ route('media.show', $media) }}"
                                        alt="{{ $media->file_name }}"
                                        class="img-thumbnail"
                                        style="width: 100px; height: 100px; object-fit: cover;"
                                    >
                                </a>
                            @else
                                {{-- AI/PSD/PDF aren't browser-renderable as images — a
                                     generic icon that still opens/downloads the real
                                     file via the same authenticated route. --}}
                                <a href="{{ route('media.show', $media) }}" target="_blank" class="d-block text-decoration-none">
                                    <div
                                        class="border rounded d-flex align-items-center justify-content-center bg-light mx-auto"
                                        style="width: 100px; height: 100px;"
                                    >
                                        <i class="fa-solid fa-file fa-2x text-muted"></i>
                                    </div>
                                </a>
                            @endif
                            <div class="small text-truncate mt-1" title="{{ $media->file_name }}">
                                {{ $media->file_name }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">
        {{ isset($design) && $design ? 'Update Design' : 'Create Design' }}
    </button>
    <a href="{{ route('catalog.designs.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
