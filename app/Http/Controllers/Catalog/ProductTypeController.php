<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductTypeRequest;
use App\Http\Requests\UpdateProductTypeRequest;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of product types.
     */
    public function index(): View
    {
        $this->authorize('viewAny', ProductType::class);

        $productTypes = ProductType::orderBy('sort_order')->orderBy('name')->get();

        return view('catalog.product-types.index', compact('productTypes'));
    }

    /**
     * Show the form for creating a new product type.
     */
    public function create(): View
    {
        $this->authorize('create', ProductType::class);

        return view('catalog.product-types.create');
    }

    /**
     * Store a newly created product type.
     */
    public function store(StoreProductTypeRequest $request): RedirectResponse
    {
        ProductType::create($request->validated());

        return redirect()->route('catalog.product-types.index')->with('status', 'Product type created successfully.');
    }

    /**
     * Show the form for editing an existing product type.
     */
    public function edit(ProductType $productType): View
    {
        $this->authorize('update', $productType);

        return view('catalog.product-types.edit', compact('productType'));
    }

    /**
     * Update the specified product type.
     */
    public function update(UpdateProductTypeRequest $request, ProductType $productType): RedirectResponse
    {
        $productType->update($request->validated());

        return redirect()->route('catalog.product-types.index')->with('status', 'Product type updated successfully.');
    }
}
