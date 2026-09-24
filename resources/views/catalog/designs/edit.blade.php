@extends('layouts.app')

@section('title', 'Edit Design')

@section('content')
    <h1 class="h3 mb-4">Edit Design</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('catalog.designs.update', $design) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('catalog.designs._form', ['design' => $design])
            </form>
        </div>
    </div>
@endsection
