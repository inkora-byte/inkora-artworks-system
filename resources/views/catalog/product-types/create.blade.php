@extends('layouts.app')

@section('title', 'New Product Type')

@section('content')
    <h1 class="h3 mb-4">New Product Type</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('catalog.product-types.store') }}">
                @csrf
                @include('catalog.product-types._form', ['productType' => null])
            </form>
        </div>
    </div>
@endsection
