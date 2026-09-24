@extends('layouts.app')

@section('title', 'New Design')

@section('content')
    <h1 class="h3 mb-4">New Design</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('catalog.designs.store') }}" enctype="multipart/form-data">
                @csrf
                @include('catalog.designs._form', ['design' => null])
            </form>
        </div>
    </div>
@endsection
