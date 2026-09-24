@extends('layouts.app')

@section('title', 'Designs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Designs</h1>
        <a href="{{ route('catalog.designs.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> New Design
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="designs-table" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Name</th>
                        <th>Artist</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($designs as $design)
                        @php
                            $previewMedia = $design->getMedia('source_files')
                                ->first(fn ($media) => in_array($media->mime_type, ['image/png', 'image/jpeg', 'image/svg+xml']));
                        @endphp
                        <tr>
                            <td>
                                @if ($previewMedia)
                                    <a href="{{ route('media.show', $previewMedia) }}" target="_blank">
                                        <img
                                            src="{{ route('media.show', $previewMedia) }}"
                                            alt="{{ $design->name }}"
                                            class="img-thumbnail"
                                            style="width: 50px; height: 50px; object-fit: cover;"
                                        >
                                    </a>
                                @else
                                    <span class="text-muted small">{{ $design->getMedia('source_files')->count() }} file(s)</span>
                                @endif
                            </td>
                            <td>{{ $design->name }}</td>
                            <td>{{ $design->artist_name ?? '—' }}</td>
                            <td>
                                <x-status-badge :status="$design->status->label()" :color="$design->status->color()" />
                            </td>
                            <td class="text-end">
                                <a href="{{ route('catalog.designs.edit', $design) }}" class="btn btn-sm btn-outline-primary">
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
            new DataTable('#designs-table');
        });
    </script>
@endsection
