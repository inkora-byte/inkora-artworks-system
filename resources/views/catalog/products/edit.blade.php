@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <h1 class="h3 mb-4">Edit Product</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('catalog.products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('catalog.products._form', [
                    'product' => $product,
                    'productTypes' => $productTypes,
                    'designs' => $designs,
                ])
            </form>
        </div>
    </div>
@endsection
