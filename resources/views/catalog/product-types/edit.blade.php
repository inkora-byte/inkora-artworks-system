@extends('layouts.app')

@section('title', 'Edit Product Type')

@section('content')
    <h1 class="h3 mb-4">Edit Product Type</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('catalog.product-types.update', $productType) }}">
                @csrf
                @method('PUT')
                @include('catalog.product-types._form', ['productType' => $productType])
            </form>
        </div>
    </div>
@endsection
