@extends('layouts.app')

@section('title', 'New Product')

@section('content')
    <h1 class="h3 mb-4">New Product</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('catalog.products.store') }}" enctype="multipart/form-data">
                @csrf
                @include('catalog.products._form', [
                    'product' => null,
                    'productTypes' => $productTypes,
                    'designs' => $designs,
                ])
            </form>
        </div>
    </div>
@endsection
